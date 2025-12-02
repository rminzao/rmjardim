<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function index()
    {
        $settings = DB::table('site_settings')
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.content.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'logo_text' => 'required|string|max:255',
            'hero_badge' => 'required|string|max:255',
            'hero_title' => 'required|string|max:255',
            'hero_title_highlight' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_button_primary' => 'required|string|max:100',
            'hero_button_secondary' => 'required|string|max:100',
            'services_tag' => 'required|string|max:255',
            'services_title' => 'required|string|max:255',
            'services_description' => 'required|string',
            'portfolio_tag' => 'required|string|max:255',
            'portfolio_title' => 'required|string|max:255',
            'portfolio_description' => 'required|string',
            'contact_tag' => 'required|string|max:255',
            'contact_title' => 'required|string|max:255',
            'contact_description' => 'required|string',
            'footer_description' => 'required|string',
            'footer_phone' => 'required|string|max:50',
            'footer_email' => 'required|email|max:255',
            'footer_address' => 'required|string|max:255',
            'footer_copyright' => 'required|string|max:255',
            'instagram_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
        ]);

        foreach ($validated as $key => $value) {
            DB::table('site_settings')
                ->updateOrInsert(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'updated_at' => now()
                    ]
                );
        }

        return redirect()->route('admin.content.index')
            ->with('success', 'Conteúdo atualizado com sucesso!');
    }
}