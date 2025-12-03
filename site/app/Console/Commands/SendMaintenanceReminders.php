<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendMaintenanceReminders extends Command
{
    protected $signature = 'maintenance:send-reminders';
    protected $description = 'Envia lembretes de manutenção para clientes contratados';

    public function handle()
    {
        $this->info('🔍 Verificando clientes que precisam de manutenção...');

        // Buscar clientes contratados
        $clients = DB::table('contact_messages')
            ->where('status', 'hired')
            ->whereNotNull('hired_at')
            ->whereNotNull('maintenance_days')
            ->get();

        if ($clients->isEmpty()) {
            $this->info('✅ Nenhum cliente contratado encontrado.');
            return 0;
        }

        $this->info("📊 Encontrados {$clients->count()} cliente(s) contratado(s)");

        $sentCount = 0;
        $today = Carbon::now();

        foreach ($clients as $client) {
            $hiredAt = Carbon::parse($client->hired_at);
            $maintenanceDays = (int) $client->maintenance_days;
            $maintenanceDate = $hiredAt->copy()->addDays($maintenanceDays);
            
            // Verificar se hoje é o dia da manutenção (ou passou)
            if ($today->greaterThanOrEqualTo($maintenanceDate)) {
                
                // Verificar se já foi enviado (evitar spam)
                $lastSent = DB::table('maintenance_reminders')
                    ->where('contact_id', $client->id)
                    ->where('maintenance_date', $maintenanceDate->format('Y-m-d'))
                    ->first();

                if (!$lastSent) {
                    $this->sendMaintenanceMessage($client, $maintenanceDays, $maintenanceDate);
                    
                    // Registrar envio
                    DB::table('maintenance_reminders')->insert([
                        'contact_id' => $client->id,
                        'maintenance_date' => $maintenanceDate->format('Y-m-d'),
                        'sent_at' => now(),
                        'created_at' => now(),
                    ]);
                    
                    $sentCount++;
                    $this->info("✅ Lembrete enviado para: {$client->name}");
                } else {
                    $this->info("⏭️  Lembrete já enviado para: {$client->name}");
                }
            }
        }

        $this->info("🎉 Processo concluído! {$sentCount} lembrete(s) enviado(s)");
        
        return 0;
    }

    private function sendMaintenanceMessage($client, $days, $maintenanceDate)
    {
        try {
            $apiUrl = config('services.wppconnect.url');
            
            // Formatar número do cliente
            $phone = preg_replace('/[^0-9]/', '', $client->phone);
            if (strlen($phone) === 11 || strlen($phone) === 10) {
                $phone = '55' . $phone;
            }
            $phone = $phone . '@c.us';
            
            // Mensagem personalizada
            $message = "🌱 *Olá {$client->name}!*\n\n"
            . "Recebi uma ligação aqui do seu jardim... 📞🌿\n"
            . "Ele avisou que já fazem *{$days} dias* desde a última manutenção\n"
            . "e que esse é o momento ideal para deixar tudo bonito novamente! ✨\n\n"
            . "Para te ajudar, liberei um *desconto exclusivo* para clientes ativos. 🎉\n"
            . "Quer garantir o seu horário antes que a agenda encha?\n\n"
            . "📱 *É só responder esta mensagem!* \n\n"
            . "_RM Jardim — Seu jardim em boas mãos_ 🤝🌿";
            
            Http::timeout(10)->post("{$apiUrl}/send-message", [
                'phone' => $phone,
                'message' => $message,
            ]);
            
            Log::info('Lembrete de manutenção enviado', [
                'client' => $client->name,
                'phone' => $client->phone,
                'days' => $days
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar lembrete de manutenção: ' . $e->getMessage());
            $this->error("❌ Erro ao enviar para {$client->name}: {$e->getMessage()}");
        }
    }
}