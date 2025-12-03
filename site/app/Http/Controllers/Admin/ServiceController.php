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

    public function getIconSvg($iconName)
    {
        $icons = [
            'TreeDeciduous' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>',
            'Scissors' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>',
            'Flower2' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'Droplets' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>',
            'Shovel' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
            'Sun' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
            'Leaf' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'Sprout' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>',
            'TreePine' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'Cloud' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>',
        ];
        
        return $icons[$iconName] ?? $icons['Leaf'];
    }
}