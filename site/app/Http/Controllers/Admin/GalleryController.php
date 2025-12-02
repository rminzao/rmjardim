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
            ->orderBy('order')
            ->get();

        return view('admin.gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'active' => 'boolean',
        ]);

        // Upload da imagem
        $path = $request->file('image')->store('gallery', 'public');

        // Buscar último order
        $lastOrder = DB::table('gallery_images')->max('order') ?? 0;

        DB::table('gallery_images')->insert([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'image_path' => $path,
            'active' => $request->boolean('active', true),
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
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'active' => 'boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'active' => $request->boolean('active'),
            'updated_at' => now(),
        ];

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

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Imagem atualizada com sucesso!');
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
}