<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Webhook recebido', $request->all());

        try {
            // Extrair dados da mensagem
            $from = $request->input('from'); // Número que enviou
            $body = $request->input('body'); // Texto da mensagem
            
            // Número do admin (buscar do banco)
            $adminNumber = DB::table('site_settings')
                ->where('key', 'whatsapp_notification')
                ->value('value');
            
            // Formatar número do admin (remover tudo que não é número)
            $adminNumberClean = preg_replace('/[^0-9]/', '', $adminNumber);
            if (!str_starts_with($adminNumberClean, '55')) {
                $adminNumberClean = '55' . $adminNumberClean;
            }
            $adminNumberFormatted = $adminNumberClean . '@c.us';
            
            // Verificar se a mensagem veio do admin
            if ($from !== $adminNumberFormatted) {
                return response()->json(['status' => 'ignored', 'reason' => 'not_admin']);
            }
            
            // Verificar se é um comando
            if (!str_starts_with($body, '#')) {
                return response()->json(['status' => 'ignored', 'reason' => 'not_command']);
            }
            
            // Processar comando
            return $this->processCommand($body);
            
        } catch (\Exception $e) {
            Log::error('Erro no webhook: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    private function processCommand($message)
    {
        // Exemplo: #contratado30 A7K9P2
        $message = trim($message);
        
        // Extrair comando e ID
        preg_match('/#([a-z]+)(\d+)\s+([A-Z0-9]{6})/i', $message, $matches);
        
        if (count($matches) < 4) {
            return response()->json(['status' => 'error', 'message' => 'Formato inválido. Use: #contratado30 ABC123']);
        }
        
        $comando = strtolower($matches[1]); // contratado
        $dias = (int)$matches[2]; // 30
        $uniqueId = strtoupper($matches[3]); // A7K9P2
        
        Log::info('Comando processado', [
            'comando' => $comando,
            'dias' => $dias,
            'unique_id' => $uniqueId
        ]);
        
        // Buscar cliente
        $client = DB::table('contact_messages')
            ->where('unique_id', $uniqueId)
            ->first();
        
        if (!$client) {
            $this->sendWhatsAppToAdmin("❌ Cliente com ID #{$uniqueId} não encontrado!");
            return response()->json(['status' => 'error', 'message' => 'Cliente não encontrado']);
        }
        
        // Processar comando
        switch ($comando) {
            case 'contratado':
            case 'contratar':
                return $this->handleContratado($client, $dias, $uniqueId);
                
            case 'cancelado':
            case 'cancelar':
                return $this->handleCancelado($client, $uniqueId);
                
            default:
                return response()->json(['status' => 'error', 'message' => 'Comando desconhecido']);
        }
    }
    
    private function handleContratado($client, $dias, $uniqueId)
    {
        DB::table('contact_messages')
            ->where('id', $client->id)
            ->update([
                'status' => 'hired',
                'hired_at' => now(),
                'maintenance_days' => $dias,
                'updated_at' => now(),
            ]);
        
        Log::info('Cliente contratado', [
            'unique_id' => $uniqueId,
            'dias' => $dias
        ]);
        
        // Enviar confirmação para o admin
        $message = "✅ *Cliente #{$uniqueId} contratado!*\n\n"
            . "👤 Nome: {$client->name}\n"
            . "📱 Telefone: {$client->phone}\n"
            . "📅 Manutenção em: {$dias} dias\n"
            . "📆 Data prevista: " . now()->addDays($dias)->format('d/m/Y');
        
        $this->sendWhatsAppToAdmin($message);
        
        return response()->json(['status' => 'success', 'message' => 'Cliente contratado']);
    }
    
    private function handleCancelado($client, $uniqueId)
    {
        DB::table('contact_messages')
            ->where('id', $client->id)
            ->update([
                'status' => 'canceled',
                'updated_at' => now(),
            ]);
        
        Log::info('Cliente cancelado', ['unique_id' => $uniqueId]);
        
        // Enviar confirmação para o admin
        $message = "🚫 *Cliente #{$uniqueId} cancelado!*\n\n"
            . "👤 Nome: {$client->name}\n"
            . "📱 Telefone: {$client->phone}";
        
        $this->sendWhatsAppToAdmin($message);
        
        return response()->json(['status' => 'success', 'message' => 'Cliente cancelado']);
    }
    
    private function sendWhatsAppToAdmin($message)
    {
        try {
            $apiUrl = config('services.wppconnect.url');
            
            // Buscar número do admin
            $whatsappNumber = DB::table('site_settings')
                ->where('key', 'whatsapp_notification')
                ->value('value');
            
            // Formatar número
            $phone = preg_replace('/[^0-9]/', '', $whatsappNumber);
            if (strlen($phone) === 11 || strlen($phone) === 10) {
                $phone = '55' . $phone;
            }
            $phone = $phone . '@c.us';
            
            Http::timeout(10)->post("{$apiUrl}/send-message", [
                'phone' => $phone,
                'message' => $message,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar WhatsApp para admin: ' . $e->getMessage());
        }
    }
}