<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // Busca configurações do site
        $settings = DB::table('site_settings')
            ->pluck('value', 'key')
            ->toArray();
        
        // Busca serviços ativos
        $services = DB::table('services')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        // Busca imagens da galeria
        $images = DB::table('gallery_images')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->limit(6)
            ->get();
        
        return view('landing', compact('settings', 'services', 'images'));
    }
}