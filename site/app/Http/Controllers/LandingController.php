<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        $settings = DB::table('site_settings')
            ->pluck('value', 'key')
            ->toArray();
        
        $services = DB::table('services')
            ->where('active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        // Agrupar imagens por serviço
        $servicePortfolios = [];
        foreach ($services as $service) {
            $images = DB::table('gallery_images')
                ->where('service_id', $service->id)
                ->orderBy('order', 'asc')
                ->get();
            
            if ($images->count() > 0) {
                $servicePortfolios[] = [
                    'service' => $service,
                    'images' => $images
                ];
            }
        }
        
        // Imagens sem categoria
        $uncategorizedImages = DB::table('gallery_images')
            ->whereNull('service_id')
            ->orderBy('order', 'asc')
            ->get();
        
        if ($uncategorizedImages->count() > 0) {
            $servicePortfolios[] = [
                'service' => (object) ['title' => 'Outros Trabalhos'],
                'images' => $uncategorizedImages
            ];
        }
        
        return view('landing', compact('settings', 'services', 'servicePortfolios'));
    }
}