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
        
        $projectPortfolios = [];
        
        $projects = DB::table('projects')
            ->where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();
        
        foreach ($projects as $project) {
            $images = DB::table('gallery_images')
                ->where('project_id', $project->id)
                ->orderBy('order', 'asc')
                ->get();
            
            if ($images->count() > 0) {
                $projectPortfolios[] = [
                    'project' => $project,
                    'images' => $images
                ];
            }
        }
        
        // Imagens sem projeto
        $uncategorizedImages = DB::table('gallery_images')
            ->whereNull('project_id')
            ->orderBy('order', 'asc')
            ->get();
        
        if ($uncategorizedImages->count() > 0) {
            $projectPortfolios[] = [
                'project' => (object) ['title' => 'Outros Trabalhos'],
                'images' => $uncategorizedImages
            ];
        }
        
        return view('landing', compact('settings', 'services', 'projectPortfolios'));
    }
}