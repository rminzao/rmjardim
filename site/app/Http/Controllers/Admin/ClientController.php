<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('contact_messages');

        // Filtro de busca
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filtro de status
        if ($request->has('filter') && $request->filter !== 'all') {
            switch ($request->filter) {
                case 'pending':
                    $query->where('status', 'new');
                    break;
                case 'hired':
                    $query->where('status', 'hired')
                          ->where(function($q) {
                              $q->whereNull('hired_at')
                                ->orWhereRaw('DATEDIFF(day, hired_at, GETDATE()) < maintenance_days');
                          });
                    break;
                case 'maintenance':
                    $query->where('status', 'hired')
                          ->whereNotNull('hired_at')
                          ->whereRaw('DATEDIFF(day, hired_at, GETDATE()) >= maintenance_days');
                    break;
            }
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calcular dias para manutenção
        $clients->each(function($client) {
            if ($client->status === 'hired' && $client->hired_at) {
                $daysElapsed = now()->diffInDays($client->hired_at);
                $client->days_until_maintenance = ($client->maintenance_days ?? 30) - $daysElapsed;
                $client->needs_maintenance = $daysElapsed >= ($client->maintenance_days ?? 30);
            }
        });

        return view('admin.clients.index', compact('clients'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
            'status' => 'required|in:new,contacted,hired,canceled',
            'whatsapp_sent' => 'boolean',
            'maintenance_days' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $data = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'status' => $validated['status'],
            'whatsapp_sent' => $request->boolean('whatsapp_sent'),
            'notes' => $validated['notes'] ?? null,
            'updated_at' => now(),
        ];

        // Se foi contratado, salva data e prazo
        if ($validated['status'] === 'hired' && !DB::table('contact_messages')->where('id', $id)->value('hired_at')) {
            $data['hired_at'] = now();
            $data['maintenance_days'] = $validated['maintenance_days'] ?? 30;
        }

        DB::table('contact_messages')->where('id', $id)->update($data);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy($id)
    {
        DB::table('contact_messages')->where('id', $id)->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }
}