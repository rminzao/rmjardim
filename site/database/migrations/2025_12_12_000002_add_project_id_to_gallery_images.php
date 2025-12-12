<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            // Remove a FK antiga de service_id
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
            
            // Adiciona FK para project_id
            $table->unsignedBigInteger('project_id')->nullable()->after('title');
            
            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            // Reverte as mudanças
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
            
            // Restaura service_id
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')
                  ->references('id')
                  ->on('services')
                  ->onDelete('cascade');
        });
    }
};