<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = DB::table('gallery_images')
            ->leftJoin('projects', 'gallery_images.project_id', '=', 'projects.id')
            ->select('gallery_images.*', 'projects.title as project_name')
            ->orderBy('gallery_images.order', 'asc')
            ->get();
        
        $settings = DB::table('site_settings')
            ->whereIn('key', ['logo_url', 'hero_image_url'])
            ->pluck('value', 'key')
            ->toArray();
        
        $projects = DB::table('projects')
            ->orderBy('order', 'asc')
            ->get();
        
        $services = DB::table('services')
            ->where('active', true)
            ->orderBy('title', 'asc')
            ->get();
        
        return view('admin.gallery.index', compact('images', 'settings', 'projects', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Upload da imagem
        $path = $request->file('image')->store('gallery', 'public');

        // Buscar último order
        $lastOrder = DB::table('gallery_images')->max('order') ?? 0;

        DB::table('gallery_images')->insert([
            'title' => $validated['title'],
            'project_id' => $validated['project_id'] ?? null,
            'image_path' => $path,
            'order' => $lastOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Imagem adicionada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [
            'updated_at' => now(),
        ];

        // Atualizar título se foi enviado
        if ($request->has('title')) {
            $data['title'] = $validated['title'];
        }

        // Atualizar project_id se foi enviado
        if ($request->has('project_id')) {
            $data['project_id'] = $validated['project_id'];
        }

        // Se enviou nova imagem
        if ($request->hasFile('image')) {
            // Deletar imagem antiga
            $oldImage = DB::table('gallery_images')->where('id', $id)->value('image_path');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            // Upload nova imagem
            $path = $request->file('image')->store('gallery', 'public');
            $data['image_path'] = $path;
        }

        DB::table('gallery_images')->where('id', $id)->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $image = DB::table('gallery_images')->where('id', $id)->first();
        
        if ($image && $image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }

        DB::table('gallery_images')->where('id', $id)->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Imagem excluída com sucesso!');
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $id => $order) {
            DB::table('gallery_images')
                ->where('id', $id)
                ->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('images/logo', 'public');
            
            DB::table('site_settings')->updateOrInsert(
                ['key' => 'logo_url'],
                ['value' => $path, 'type' => 'text', 'group' => 'images', 'updated_at' => now()]
            );
        }

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('images/hero', 'public');
            
            DB::table('site_settings')->updateOrInsert(
                ['key' => 'hero_image_url'],
                ['value' => $path, 'type' => 'text', 'group' => 'images', 'updated_at' => now()]
            );
        }

        return redirect()->route('admin.gallery.index')->with('success', 'Imagens atualizadas!');
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service_id' => 'nullable|exists:services,id',
        ]);

        $lastOrder = DB::table('projects')->max('order') ?? 0;

        DB::table('projects')->insert([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'service_id' => $validated['service_id'] ?? null,
            'order' => $lastOrder + 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Projeto criado com sucesso!');
    }

    public function updateProject(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service_id' => 'nullable|exists:services,id',
        ]);

        DB::table('projects')->where('id', $id)->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'service_id' => $validated['service_id'] ?? null,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyProject($id)
    {
        DB::table('projects')->where('id', $id)->delete();
        
        return redirect()->route('admin.gallery.index')
            ->with('success', 'Projeto excluído com sucesso!');
    }
}