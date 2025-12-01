<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

        $apiUrl = config('services.wppconnect.url');
        $whatsappNumber = DB::table('site_settings')
            ->where('key', 'whatsapp_notification')
            ->value('value');
            
        try {
            $messageToAdmin = "🌱 *Novo contato - RM Jardim*\n\n"
                . "👤 *Nome:* {$validated['name']}\n"
                . "📱 *Telefone:* {$validated['phone']}\n"
                . "💬 *Mensagem:* {$validated['message']}";

            $response = Http::post($apiUrl . '/send-message', [
                'phone' => $whatsappNumber,
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
            \Log::error('Erro ao enviar notificação admin: ' . $e->getMessage());
        }

        // msg de boas vindas
        try {
            $firstName = explode(' ', $validated['name'])[0];
            
            $messageToClient = "Olá *{$firstName}*! 👋🌱\n\n"
                . "Obrigado por entrar em contato com a *RM Jardim*!\n\n"
                . "Recebemos sua mensagem e em breve nossa equipe entrará em contato para apresentar nossos serviços e fazer um orçamento personalizado para você.\n\n"
                . "🌿 Confira alguns dos nossos trabalhos abaixo!\n\n"
                . "Enquanto isso, fique à vontade para nos chamar aqui no WhatsApp se tiver alguma dúvida!\n\n"
                . "Atenciosamente,\n"
                . "*Equipe RM Jardim* 🌱";

            Http::post($apiUrl . '/send-message', [
                'phone' => $validated['phone'],
                'message' => $messageToClient,
            ]);

            sleep(2);
            
            $publicPath = public_path();
            
            Http::timeout(60)->post($apiUrl . '/send-image-file', [
                'phone' => $validated['phone'],
                'imagePath' => $publicPath . '/images/image-1.png',
                'caption' => '🌿 Projeto de paisagismo - Exemplo 1'
            ]);

            sleep(2);

            Http::timeout(60)->post($apiUrl . '/send-image-file', [
                'phone' => $validated['phone'],
                'imagePath' => $publicPath . '/images/image-2.png',
                'caption' => '🌱 Projeto de jardinagem'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao enviar mensagem cliente: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso! Enviamos uma confirmação no seu WhatsApp. Entraremos em contato em breve.');
    }
}