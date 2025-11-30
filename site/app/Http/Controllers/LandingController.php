<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // Busca imagens da galeria (ativas, ordenadas)
        $images = DB::table('gallery_images')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->limit(6)
            ->get();

        // Busca configurações do site
        $settings = DB::table('site_settings')
            ->pluck('value', 'key')
            ->toArray();

        return view('landing', compact('images', 'settings'));
    }
}