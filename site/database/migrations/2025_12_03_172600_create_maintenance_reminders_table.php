<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_reminders', function ($table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->date('maintenance_date');
            $table->timestamp('sent_at');
            $table->timestamps();
            
            $table->foreign('contact_id')
                  ->references('id')
                  ->on('contact_messages')
                  ->onDelete('cascade');
                  
            $table->unique(['contact_id', 'maintenance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_reminders');
    }
};