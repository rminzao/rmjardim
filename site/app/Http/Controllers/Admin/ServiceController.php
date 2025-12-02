<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $services = DB::table('services')
            ->orderBy('order')
            ->get();

        $icons = [
            'TreeDeciduous',
            'Scissors',
            'Flower2',
            'Droplets',
            'Shovel',
            'Sun',
            'Leaf',
            'Sprout',
            'TreePine',
            'Cloud',
        ];

        return view('admin.services.index', compact('services', 'icons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:50',
            'active' => 'boolean',
        ]);

        // Buscar último order
        $lastOrder = DB::table('services')->max('order') ?? 0;

        DB::table('services')->insert([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon' => $validated['icon'],
            'active' => $request->boolean('active', true),
            'order' => $lastOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Serviço adicionado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:50',
            'active' => 'boolean',
        ]);

        DB::table('services')->where('id', $id)->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon' => $validated['icon'],
            'active' => $request->boolean('active'),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.services.index')
            ->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy($id)
    {
        DB::table('services')->where('id', $id)->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Serviço excluído com sucesso!');
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $id => $order) {
            DB::table('services')
                ->where('id', $id)
                ->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}