<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Helpers\WhatsAppHelper;

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

        // Gerar unique_id aleatório
        $uniqueId = strtoupper(Str::random(6));

        // Garantir que é único
        while (DB::table('contact_messages')->where('unique_id', $uniqueId)->exists()) {
            $uniqueId = strtoupper(Str::random(6));
        }

        // Salva no banco
        $contactId = DB::table('contact_messages')->insertGetId([
            'unique_id' => $uniqueId,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'status' => 'new',
            'whatsapp_sent' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buscar número de notificação
        $whatsappNumber = DB::table('site_settings')
            ->where('key', 'whatsapp_notification')
            ->value('value');

        // Enviar notificação para o admin
        $messageToAdmin = "🌱 *Novo contato - RM Jardim*\n\n"
            . "🆔 *ID:* #{$uniqueId}\n"
            . "👤 *Nome:* {$validated['name']}\n"
            . "📱 *Telefone:* {$validated['phone']}\n"
            . "💬 *Mensagem:* {$validated['message']}";

        $adminSent = WhatsAppHelper::sendMessage($whatsappNumber, $messageToAdmin);

        if ($adminSent) {
            DB::table('contact_messages')
                ->where('id', $contactId)
                ->update([
                    'whatsapp_sent' => true,
                    'whatsapp_sent_at' => now(),
                ]);
        }

        // Mensagem de boas-vindas ao cliente
        $firstName = explode(' ', $validated['name'])[0];
        $messageToClient = "Olá *{$firstName}*! 👋🌱\n\n"
            . "Obrigado por entrar em contato com a *RM Jardim*!\n\n"
            . "Recebemos sua mensagem e em breve nossa equipe entrará em contato.\n\n"
            . "Atenciosamente,\n*Equipe RM Jardim* 🌱";

        WhatsAppHelper::sendMessage($validated['phone'], $messageToClient);

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso! Enviamos uma confirmação no seu WhatsApp. Entraremos em contato em breve.');
    }
}