<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, boolean
            $table->string('group')->default('general'); // general, contact, social
            $table->timestamps();
        });

        // Insere configurações padrão
        DB::table('site_settings')->insert([
            ['key' => 'site_name', 'value' => 'RM Jardim', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Serviços profissionais de jardinagem', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'phone', 'value' => '5519983532645', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'contato@rmjardim.com.br', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'whatsapp_notification', 'value' => '5519983532645', 'type' => 'text', 'group' => 'contact'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};