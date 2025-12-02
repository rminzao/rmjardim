<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validação
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // Salva no banco
        $contactId = DB::table('contact_messages')->insertGetId([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'status' => 'new',
            'whatsapp_sent' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Configurações da API
        $apiUrl = config('services.wppconnect.url');
        
        // Buscar número de notificação
        $whatsappNumber = DB::table('site_settings')
            ->where('key', 'whatsapp_notification')
            ->value('value');

        // Enviar notificação para o admin
        try {
            $messageToAdmin = "🌱 *Novo contato - RM Jardim*\n\n"
                . "👤 *Nome:* {$validated['name']}\n"
                . "📱 *Telefone:* {$validated['phone']}\n"
                . "💬 *Mensagem:* {$validated['message']}";

            $response = Http::timeout(30)->post("{$apiUrl}/send-message", [
                'phone' => $this->formatPhone($whatsappNumber),
                'message' => $messageToAdmin,
            ]);

            if ($response->successful()) {
                DB::table('contact_messages')
                    ->where('id', $contactId)
                    ->update([
                        'whatsapp_sent' => true,
                        'whatsapp_sent_at' => now(),
                    ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação admin: ' . $e->getMessage());
        }

        // Mensagem de boas-vindas ao cliente
        try {
            $firstName = explode(' ', $validated['name'])[0];
            $messageToClient = "Olá *{$firstName}*! 👋🌱\n\n"
                . "Obrigado por entrar em contato com a *RM Jardim*!\n\n"
                . "Recebemos sua mensagem e em breve nossa equipe entrará em contato.\n\n"
                . "Atenciosamente,\n*Equipe RM Jardim* 🌱";

            Http::timeout(30)->post("{$apiUrl}/send-message", [
                'phone' => $this->formatPhone($validated['phone']),
                'message' => $messageToClient,
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao enviar mensagem cliente: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso! Enviamos uma confirmação no seu WhatsApp. Entraremos em contato em breve.');
    }

    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($phone) === 11 || strlen($phone) === 10) {
            $phone = '55' . $phone;
        }
        
        return $phone . '@c.us';
    }
}