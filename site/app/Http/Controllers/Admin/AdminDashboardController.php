<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'total_contacts' => DB::table('contact_messages')->count(),
            'pending_contacts' => DB::table('contact_messages')->where('status', 'new')->count(),
            'hired_clients' => DB::table('contact_messages')->where('status', 'hired')->count(),
            'needs_maintenance' => $this->getMaintenanceCount(),
        ];

        $query = DB::table('contact_messages');

        // Filtro de busca
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filtro de status
        if (request()->has('filter') && request('filter') !== 'all') {
            switch (request('filter')) {
                case 'pending':
                    $query->where('status', 'new');
                    break;
                case 'hired':
                    $query->where('status', 'hired');
                    break;
            }
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(20);

        // Converter created_at
        $clients->getCollection()->transform(function($client) {
            $client->created_at = \Carbon\Carbon::parse($client->created_at);
            if ($client->hired_at) {
                $client->hired_at = \Carbon\Carbon::parse($client->hired_at);
            }
            if ($client->whatsapp_sent_at) {
                $client->whatsapp_sent_at = \Carbon\Carbon::parse($client->whatsapp_sent_at);
            }
            return $client;
        });

        // Calcular dias para manutenção
        $clients->each(function($client) {
            if ($client->status === 'hired' && $client->hired_at) {
                $daysElapsed = now()->diffInDays($client->hired_at);
                $client->days_until_maintenance = ($client->maintenance_days ?? 30) - $daysElapsed;
                $client->needs_maintenance = $daysElapsed >= ($client->maintenance_days ?? 30);
            }
        });

        return view('admin.dashboard', compact('stats', 'clients'));
    }

    private function getMaintenanceCount()
    {
        return DB::table('contact_messages')
            ->where('status', 'hired')
            ->whereNotNull('hired_at')
            ->whereNotNull('maintenance_days')
            ->get()
            ->filter(function ($client) {
                $daysElapsed = now()->diffInDays($client->hired_at);
                return $daysElapsed >= ($client->maintenance_days ?? 30);
            })
            ->count();
    }
}