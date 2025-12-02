<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->timestamp('hired_at')->nullable()->after('whatsapp_sent_at');
            $table->integer('maintenance_days')->nullable()->default(30)->after('hired_at');
            $table->text('notes')->nullable()->after('maintenance_days');
        });
        
        // Atualizar valores de status existentes
        DB::statement("UPDATE contact_messages SET status = 'new' WHERE status = 'new'");
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['hired_at', 'maintenance_days', 'notes']);
        });
    }
};