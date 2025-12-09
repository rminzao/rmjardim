<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela principal de propostas
        Schema::create('proposal_services', function (Blueprint $table) {
            $table->id();
            
            // Dados do cliente
            $table->string('client_name')->nullable();
            $table->string('client_document')->nullable(); // CPF ou CNPJ
            $table->enum('client_document_type', ['cpf', 'cnpj'])->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_address')->nullable();
            $table->string('client_city')->nullable();
            $table->string('client_state', 2)->nullable();
            $table->string('client_zipcode', 10)->nullable();
            
            // Dados da empresa (preenchido automaticamente se CNPJ)
            $table->string('company_name')->nullable(); // Razão Social
            $table->string('company_trade_name')->nullable(); // Nome Fantasia
            
            // Valores
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            
            // Condições
            $table->string('payment_conditions')->default('50% entrada + 50% na conclusão');
            $table->integer('validity_days')->default(15);
            $table->text('notes')->nullable();
            
            // Status e controle
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected'])->default('draft');
            $table->string('pdf_path')->nullable();
            $table->timestamp('sent_at')->nullable();
            
            $table->timestamps();
        });

        // Tabela de itens da proposta
        Schema::create('proposal_service_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_service_id')->constrained()->onDelete('cascade');
            
            $table->string('description');
            $table->enum('unit', ['un', 'm²', 'm', 'hr', 'dia'])->default('un');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->integer('order')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_service_items');
        Schema::dropIfExists('proposal_services');
    }
};