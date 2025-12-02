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

        // Últimos contatos
        $recent_contacts = DB::table('contact_messages')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_contacts'));
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