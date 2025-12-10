<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppManagerController extends Controller
{
    private function getApiUrl()
    {
        return config('services.wppconnect.url', 'http://localhost:3002');
    }

    // Renderiza a página
    public function index()
    {
        return view('admin.whatsapp.index');
    }

    // API Proxy - Status
    public function status()
    {
        try {
            $response = Http::timeout(5)->get($this->getApiUrl() . '/status');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['connected' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // API Proxy - QR Code
    public function qrcode()
    {
        try {
            $response = Http::timeout(5)->get($this->getApiUrl() . '/qrcode');
            if ($response->successful()) {
                return response()->json($response->json());
            }
            return response()->json(['error' => 'QR Code não disponível'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // API Proxy - Logs
    public function logs()
    {
        try {
            $response = Http::timeout(5)->get($this->getApiUrl() . '/logs');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['logs' => []], 500);
        }
    }

    // API Proxy - Conectar
    public function connect()
    {
        try {
            $response = Http::timeout(10)->post($this->getApiUrl() . '/connect');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // API Proxy - Desconectar
    public function disconnect()
    {
        try {
            $response = Http::timeout(10)->post($this->getApiUrl() . '/disconnect');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // API Proxy - Reiniciar
    public function restart()
    {
        try {
            $response = Http::timeout(10)->post($this->getApiUrl() . '/restart');
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}