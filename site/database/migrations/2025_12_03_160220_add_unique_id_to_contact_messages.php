<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Adicionar coluna SEM unique
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('unique_id', 10)->nullable()->after('id');
        });

        // 2. Gerar IDs para registros existentes
        DB::table('contact_messages')->whereNull('unique_id')->get()->each(function ($contact) {
            DB::table('contact_messages')
                ->where('id', $contact->id)
                ->update(['unique_id' => strtoupper(Str::random(6))]);
        });

        // 3. AGORA adicionar constraint unique
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->unique('unique_id');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropUnique(['unique_id']);
            $table->dropColumn('unique_id');
        });
    }
};