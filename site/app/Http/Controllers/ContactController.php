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

        // Envia pro WhatsApp via API
        try {
            $whatsappNumber = DB::table('site_settings')
                ->where('key', 'whatsapp_notification')
                ->value('value');

            $messageText = "🌱 *Novo contato - RM Jardim*\n\n"
                . "👤 *Nome:* {$validated['name']}\n"
                . "📱 *Telefone:* {$validated['phone']}\n"
                . "💬 *Mensagem:* {$validated['message']}";

            $response = Http::post('http://localhost:3000/send-message', [
                'phone' => $whatsappNumber,
                'message' => $messageText,
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
            // falha sileciosamente
        }

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }
}