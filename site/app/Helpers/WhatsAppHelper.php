<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsAppHelper
{
    public static function sendMessage($phone, $message)
    {
        $apiUrl = config('services.wppconnect.url');
        
        $formattedPhone = self::formatPhone($phone);
        
        try {
            $response = Http::timeout(10)->post("{$apiUrl}/send-message", [
                'phone' => $formattedPhone,
                'message' => $message,
            ]);
            
            if ($response->successful()) {
                return true;
            }
        } catch (\Exception $e) {
            // Falhou, ajustar o número
        }
        
        // Segunda tentativa com ajuste do 9
        $adjustedPhone = self::adjustNinthDigit($formattedPhone);
        
        if ($adjustedPhone && $adjustedPhone !== $formattedPhone) {
            try {
                $response = Http::timeout(10)->post("{$apiUrl}/send-message", [
                    'phone' => $adjustedPhone,
                    'message' => $message,
                ]);
                
                if ($response->successful()) {
                    return true;
                }
            } catch (\Exception $e) {
                // Segunda tentativa também falhou
            }
        }
        
        return false;
    }
    
    private static function formatPhone($phone)
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($clean) === 11 || strlen($clean) === 10) {
            $clean = '55' . $clean;
        }
        return $clean . '@c.us';
    }
    
    private static function adjustNinthDigit($phone)
    {
        $number = str_replace('@c.us', '', $phone);
        
        // 55 + DDD (2) + número
        if (strlen($number) === 13) {
            if ($number[4] === '9') {
                $adjusted = substr($number, 0, 4) . substr($number, 5);
                return $adjusted . '@c.us';
            }
        } elseif (strlen($number) === 12) {
            $adjusted = substr($number, 0, 4) . '9' . substr($number, 4);
            return $adjusted . '@c.us';
        }
        
        return null;
    }
}