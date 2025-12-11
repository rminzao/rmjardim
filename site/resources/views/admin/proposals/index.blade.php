@extends('layouts.admin')

@section('title', 'Propostas de Serviço')
@section('page-title', 'Propostas')
@section('page-description', 'Crie e gerencie propostas em PDF')

@section('content')
<div class="space-y-6" x-data="proposalsManager()" x-init="init()">
    
    <!-- Header com Busca e Filtro -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <div class="flex flex-1 gap-2">
            <!-- Campo de Busca -->
            <div class="relative flex-1 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Buscar propostas..."
                    class="pl-9 w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                >
            </div>
            
            <!-- Filtro -->
            <div class="relative w-[140px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <select 
                    x-model="filter" 
                    class="pl-9 w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                >
                    <option value="all">Todas</option>
                    <option value="draft">Rascunho</option>
                    <option value="sent">Enviadas</option>
                    <option value="approved">Aprovadas</option>
                    <option value="rejected">Rejeitadas</option>
                </select>
            </div>
        </div>
        
        <!-- Botão Nova Proposta -->
        <button 
            @click="openModal()"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nova Proposta
        </button>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Card Total -->
        <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] p-4">
            <div class="text-sm text-gray-500 mb-1">Total</div>
            <div class="text-2xl font-bold text-[hsl(150,30%,15%)]" x-text="proposals.length">0</div>
        </div>
        
        <!-- Card Rascunhos -->
        <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] p-4">
            <div class="text-sm text-gray-500 mb-1">Rascunhos</div>
            <div class="text-2xl font-bold text-[hsl(150,30%,15%)]" x-text="proposals.filter(p => p.status === 'draft').length">0</div>
        </div>
        
        <!-- Card Enviadas -->
        <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] p-4">
            <div class="text-sm text-gray-500 mb-1">Enviadas</div>
            <div class="text-2xl font-bold text-[hsl(150,30%,15%)]" x-text="proposals.filter(p => p.status === 'sent').length">0</div>
        </div>
        
        <!-- Card Aprovadas -->
        <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] p-4">
            <div class="text-sm text-[hsl(142,50%,35%)] mb-1">Aprovadas</div>
            <div class="text-2xl font-bold text-[hsl(142,50%,35%)]" x-text="proposals.filter(p => p.status === 'approved').length">0</div>
        </div>
    </div>

    <!-- Tabela de Propostas -->
    <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[hsl(60,30%,96%)] border-b border-[hsl(90,20%,85%)]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telefone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[hsl(90,20%,85%)]">
                    <template x-for="proposal in filteredProposals" :key="proposal.id">
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-[hsl(150,30%,15%)]" x-text="proposal.client_name || 'Sem nome'"></div>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4">
                                <div class="text-sm text-gray-500" x-text="proposal.client_phone || '-'"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-[hsl(150,30%,15%)]" x-text="formatCurrency(proposal.total)"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span 
                                    class="px-2 py-1 text-xs font-medium rounded-full"
                                    :class="{
                                        'bg-gray-100 text-gray-700': proposal.status === 'draft',
                                        'bg-blue-100 text-blue-700': proposal.status === 'sent',
                                        'bg-green-100 text-green-700': proposal.status === 'approved',
                                        'bg-red-100 text-red-700': proposal.status === 'rejected'
                                    }"
                                    x-text="statusLabels[proposal.status]"
                                ></span>
                            </td>
                            <td class="hidden sm:table-cell px-6 py-4 text-sm text-gray-500">
                                <span x-text="formatDate(proposal.created_at)"></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-1">
                                    <!-- Botão Download PDF -->
                                    <button 
                                        @click="generatePDF(proposal.id)"
                                        class="p-2 text-[hsl(142,50%,35%)] hover:bg-[hsl(142,50%,35%)]/10 rounded-lg transition-colors"
                                        title="Gerar PDF"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                    </button>
                                    <!-- Botão WhatsApp -->
                                    <button 
                                        @click="sendWhatsApp(proposal)"
                                        class="p-2 text-[hsl(142,50%,35%)] hover:bg-[hsl(142,50%,35%)]/10 rounded-lg transition-colors"
                                        title="Enviar WhatsApp"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- Linha vazia -->
                    <tr x-show="filteredProposals.length === 0">
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Nenhuma proposta encontrada
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Criação -->
    <div 
        x-show="showModal" 
        x-cloak
        class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
        @click.self="closeModal()"
        style="display: none;"
    >
        <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" @click.stop>
            <!-- Header do Modal -->
            <div class="p-6 border-b border-[hsl(90,20%,85%)]">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-6 h-6 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="font-display text-xl font-semibold text-[hsl(150,30%,15%)]">Nova Proposta</h3>
                </div>
                <p class="text-sm text-gray-500">Preencha os dados para gerar uma proposta profissional</p>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="p-6 space-y-6">
                <!-- SEÇÃO 1: Dados do Cliente -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-[hsl(142,50%,35%)] text-white text-sm font-medium">1</span>
                        <h4 class="font-semibold text-[hsl(150,30%,15%)]">Dados do Cliente</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- CPF/CNPJ com Auto-preenchimento -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">CPF/CNPJ</label>
                            <div class="flex gap-2">
                                <div class="flex-1 relative">
                                    <input 
                                        type="text" 
                                        x-model="document"
                                        @input="documentMask()"
                                        placeholder="000.000.000-00 ou 00.000.000/0000-00"
                                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                                    >
                                    <div x-show="documentLoading" class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <svg class="animate-spin h-4 w-4 text-[hsl(142,50%,35%)]" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <button 
                                    @click="validateDocument()"
                                    type="button"
                                    :disabled="documentLoading"
                                    class="px-4 py-2 rounded-lg bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span x-show="!documentLoading">Validar</span>
                                    <span x-show="documentLoading">...</span>
                                </button>
                            </div>
                            <p x-show="documentError" class="text-xs text-red-600 mt-1" x-text="documentError"></p>
                            <p x-show="documentSuccess" class="text-xs text-green-600 mt-1" x-text="documentSuccess"></p>
                        </div>
                        
                        <!-- Nome (auto-preenchido se CNPJ) -->
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Nome *</label>
                            <input 
                                type="text" 
                                x-model="clientName"
                                placeholder="Nome completo do cliente"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        
                        <!-- Telefone (auto-preenchido se CNPJ) -->
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Telefone *</label>
                            <input 
                                type="text" 
                                x-model="clientPhone"
                                @input="phoneMask()"
                                placeholder="(00) 00000-0000"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        
                        <!-- Email (auto-preenchido se CNPJ) -->
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Email</label>
                            <input 
                                type="email" 
                                x-model="clientEmail"
                                placeholder="email@exemplo.com"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        
                        <!-- Endereço (auto-preenchido se CNPJ) -->
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Endereço *</label>
                            <input 
                                type="text" 
                                x-model="clientAddress"
                                placeholder="Rua, número"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        
                        <!-- Cidade (auto-preenchida se CNPJ) -->
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Cidade</label>
                            <input 
                                type="text" 
                                x-model="clientCity"
                                placeholder="Cidade"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        
                        <!-- Estado (auto-preenchido se CNPJ) -->
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Estado</label>
                            <input 
                                type="text" 
                                x-model="clientState"
                                placeholder="UF"
                                maxlength="2"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        
                        <!-- CEP (auto-preenchido se CNPJ) -->
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">CEP</label>
                            <input 
                                type="text" 
                                x-model="clientZipcode"
                                @input="cepMask()"
                                placeholder="00000-000"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        
                        <!-- Nome Fantasia (apenas CNPJ) -->
                        <div x-show="companyName">
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Razão Social</label>
                            <input 
                                type="text" 
                                x-model="companyName"
                                disabled
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-gray-100 text-gray-600"
                            >
                        </div>
                        
                        <!-- Razão Social (apenas CNPJ) -->
                        <div x-show="companyTradeName">
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Nome Fantasia</label>
                            <input 
                                type="text" 
                                x-model="companyTradeName"
                                disabled
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-gray-100 text-gray-600"
                            >
                        </div>
                    </div>
                </div>

                <!-- SEÇÃO 2: Serviços -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-[hsl(142,50%,35%)] text-white text-sm font-medium">2</span>
                            <h4 class="font-semibold text-[hsl(150,30%,15%)]">Serviços</h4>
                        </div>
                        <button 
                            @click="addService()"
                            type="button"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm border border-[hsl(90,20%,85%)] rounded-lg bg-white text-[hsl(150,30%,15%)] hover:bg-[hsl(60,30%,96%)] transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Adicionar
                        </button>
                    </div>

                    <div x-show="services.length === 0" class="text-center py-8 border-2 border-dashed border-[hsl(90,20%,85%)] rounded-lg">
                        <p class="text-sm text-gray-500">Clique em "Adicionar" para incluir serviços</p>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(service, index) in services" :key="service.id">
                            <div class="grid grid-cols-12 gap-2 p-3 bg-[hsl(60,30%,96%)] rounded-lg">
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-xs font-medium text-[hsl(150,30%,15%)] mb-1">Descrição</label>
                                    <input 
                                        type="text" 
                                        x-model="service.description"
                                        placeholder="Ex: Plantio de grama"
                                        class="w-full px-2 py-1.5 text-sm rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                                    >
                                </div>
                                
                                <div class="col-span-4 md:col-span-2">
                                    <label class="block text-xs font-medium text-[hsl(150,30%,15%)] mb-1">Unidade</label>
                                    <select 
                                        x-model="service.unit"
                                        class="w-full px-2 py-1.5 text-sm rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                                    >
                                        <option value="un">Unidade</option>
                                        <option value="m²">m²</option>
                                        <option value="m">Metro</option>
                                        <option value="hr">Hora</option>
                                        <option value="dia">Dia</option>
                                    </select>
                                </div>
                                
                                <div class="col-span-3 md:col-span-1">
                                    <label class="block text-xs font-medium text-[hsl(150,30%,15%)] mb-1">Qtd</label>
                                    <input 
                                        type="number" 
                                        min="1"
                                        x-model="service.quantity"
                                        @input="updateServiceTotal(index)"
                                        class="w-full px-2 py-1.5 text-sm rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                                    >
                                </div>
                                
                                <div class="col-span-4 md:col-span-2">
                                    <label class="block text-xs font-medium text-[hsl(150,30%,15%)] mb-1">Valor Unit.</label>
                                    <input 
                                        type="number" 
                                        min="0"
                                        step="0.01"
                                        x-model="service.unitPrice"
                                        @input="updateServiceTotal(index)"
                                        class="w-full px-2 py-1.5 text-sm rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                                    >
                                </div>
                                
                                <div class="col-span-4 md:col-span-2">
                                    <label class="block text-xs font-medium text-[hsl(150,30%,15%)] mb-1">Total</label>
                                    <div class="px-2 py-1.5 text-sm font-medium bg-white border border-[hsl(90,20%,85%)] rounded-lg text-[hsl(150,30%,15%)]" x-text="formatCurrency(service.total)"></div>
                                </div>
                                
                                <div class="col-span-1 flex items-end">
                                    <button 
                                        @click="removeService(index)"
                                        type="button"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Totais -->
                    <div x-show="services.length > 0" class="flex flex-col items-end gap-2 pt-4 border-t border-[hsl(90,20%,85%)]">
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium w-32 text-right" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-gray-600">Desconto:</span>
                            <input 
                                type="number" 
                                min="0"
                                step="0.01"
                                x-model="discount"
                                class="w-32 px-2 py-1.5 text-right text-sm rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        <div class="flex items-center gap-4 text-lg mt-2">
                            <span class="font-semibold text-[hsl(142,50%,35%)]">Total:</span>
                            <span class="font-bold text-[hsl(142,50%,35%)] w-32 text-right" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>
                </div>

                <!-- SEÇÃO 3: Condições -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-[hsl(142,50%,35%)] text-white text-sm font-medium">3</span>
                        <h4 class="font-semibold text-[hsl(150,30%,15%)]">Condições</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Condições de Pagamento</label>
                            <input 
                                type="text" 
                                x-model="paymentConditions"
                                placeholder="Ex: 50% entrada + 50% conclusão"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Validade (dias)</label>
                            <input 
                                type="number" 
                                min="1"
                                x-model="validity"
                                placeholder="Ex: 15"
                                class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>

                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Observações</label>
                        <textarea 
                            rows="3"
                            x-model="notes"
                            placeholder="Informações adicionais, garantias, etc."
                            class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Footer do Modal -->
            <div class="p-6 border-t border-[hsl(90,20%,85%)] flex justify-end gap-3">
                <button 
                    @click="closeModal()"
                    type="button"
                    class="px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] hover:bg-[hsl(60,30%,96%)] transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    @click="saveProposal()"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Salvar Proposta
                </button>
            </div>
        </div>
    </div>

</div>

<script>
    function proposalsManager() {
        return {
            document: '',
            documentType: '',
            documentLoading: false,
            documentError: '',
            documentSuccess: '',
            clientCity: '',
            clientState: '',
            clientZipcode: '',
            companyName: '',
            companyTradeName: '',

            proposals: [],
            search: '',
            filter: 'all',
            showModal: false,
            
            // Form data
            clientName: '',
            clientPhone: '',
            clientEmail: '',
            clientAddress: '',
            services: [],
            discount: 0,
            paymentConditions: '',
            validity: '',
            notes: '',
            
            statusLabels: {
                draft: 'Rascunho',
                sent: 'Enviada',
                approved: 'Aprovada',
                rejected: 'Rejeitada'
            },
            
            async init() {
                await this.loadProposals();
            },
            
            async loadProposals() {
                try {
                    const response = await fetch('/api/admin/proposals', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        this.proposals = await response.json();
                    }
                } catch (error) {
                    console.error('Erro ao carregar propostas:', error);
                }
            },
            
            get filteredProposals() {
                return this.proposals.filter(p => {
                    const matchesSearch = !this.search || 
                        (p.client_name && p.client_name.toLowerCase().includes(this.search.toLowerCase())) ||
                        (p.client_phone && p.client_phone.includes(this.search));
                    const matchesFilter = this.filter === 'all' || p.status === this.filter;
                    return matchesSearch && matchesFilter;
                });
            },
            
            get subtotal() {
                return this.services.reduce((sum, s) => sum + (parseFloat(s.total) || 0), 0);
            },
            
            get total() {
                return this.subtotal - (parseFloat(this.discount) || 0);
            },
            
            addService() {
                this.services.push({
                    id: Date.now(),
                    description: '',
                    unit: 'un',
                    quantity: 1,
                    unitPrice: 0,
                    total: 0
                });
            },
            
            updateServiceTotal(index) {
                const service = this.services[index];
                service.total = (parseFloat(service.quantity) || 0) * (parseFloat(service.unitPrice) || 0);
            },
            
            removeService(index) {
                this.services.splice(index, 1);
            },
            
            async saveProposal() {
                if (!this.clientName || !this.clientPhone || !this.clientAddress || this.services.length === 0) {
                    alert('Preencha todos os campos obrigatórios');
                    return;
                }

                try {
                    const response = await fetch('/api/admin/proposals', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            client_document: this.document.replace(/\D/g, ''),
                            client_document_type: this.documentType,
                            company_name: this.companyName,
                            company_trade_name: this.companyTradeName,
                            client_name: this.clientName,
                            client_phone: this.clientPhone.replace(/\D/g, ''),
                            client_email: this.clientEmail,
                            client_address: this.clientAddress,
                            client_city: this.clientCity,
                            client_state: this.clientState,
                            client_zipcode: this.clientZipcode.replace(/\D/g, ''),
                            services: this.services.map(s => ({
                                description: s.description,
                                unit: s.unit,
                                quantity: parseFloat(s.quantity) || 0,
                                unit_price: parseFloat(s.unitPrice) || 0
                            })),
                            discount: parseFloat(this.discount) || 0,
                            payment_conditions: this.paymentConditions,
                            validity_days: parseInt(this.validity) || null,
                            notes: this.notes
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        alert('Proposta criada com sucesso!');
                        this.closeModal();
                        await this.loadProposals();
                    } else {
                        let errorMessage = data.message || 'Erro ao criar proposta';
                        
                        if (data.errors) {
                            const errorList = Object.values(data.errors).flat().join('\n');
                            errorMessage += ':\n' + errorList;
                        }
                        
                        alert(errorMessage);
                        console.error('Erro detalhado:', data);
                    }
                } catch (error) {
                    console.error('Erro ao salvar proposta:', error);
                    alert('Erro ao salvar proposta: ' + error.message);
                }
            },
            
            formatCurrency(value) {
                return new Intl.NumberFormat('pt-BR', { 
                    style: 'currency', 
                    currency: 'BRL' 
                }).format(value || 0);
            },
            
            formatDate(date) {
                return new Date(date).toLocaleDateString('pt-BR');
            },
            
            async generatePDF(id) {
                try {
                    const response = await fetch(`/api/admin/proposals/${id}/generate-pdf`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (response.ok) {
                        window.open(`/api/admin/proposals/${id}/download-pdf`, '_blank');
                        alert('PDF gerado com sucesso!');
                    }
                } catch (error) {
                    console.error('Erro ao gerar PDF:', error);
                    alert('Erro ao gerar PDF');
                }
            },
            
            sendWhatsApp(proposal) {
                const message = encodeURIComponent(
                    `Olá ${proposal.client_name}! Segue a proposta de serviço da RMJardim no valor de ${this.formatCurrency(proposal.total)}. Em breve enviarei o PDF detalhado.`
                );
                const phone = proposal.client_phone.replace(/\D/g, '');
                window.open(`https://wa.me/55${phone}?text=${message}`, '_blank');
            },
            
            openModal() {
                this.showModal = true;
            },
            
            closeModal() {
                this.showModal = false;
                this.resetForm();
            },

            documentMask() {
                let value = this.document.replace(/\D/g, '');
                
                if (value.length <= 11) {
                    this.documentType = 'cpf';
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                } else {
                    this.documentType = 'cnpj';
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                }
                
                this.document = value;
            },

            phoneMask() {
                let value = this.clientPhone.replace(/\D/g, '');
                
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                
                this.clientPhone = value;
            },

            cepMask() {
                let value = this.clientZipcode.replace(/\D/g, '');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                this.clientZipcode = value;
            },

            async validateDocument() {
                this.documentError = '';
                this.documentSuccess = '';
                
                const cleanDoc = this.document.replace(/\D/g, '');
                
                if (cleanDoc.length === 11) {
                    await this.validateCPF(cleanDoc);
                } else if (cleanDoc.length === 14) {
                    await this.fetchCNPJData(cleanDoc);
                } else {
                    this.documentError = 'CPF ou CNPJ inválido';
                }
            },

            async validateCPF(cpf) {
                this.documentLoading = true;
                
                try {
                    const response = await fetch('/api/admin/validate-cpf', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ cpf })
                    });
                    
                    const data = await response.json();
                    
                    if (data.valid) {
                        this.documentSuccess = 'CPF válido!';
                    } else {
                        this.documentError = 'CPF inválido';
                    }
                } catch (error) {
                    this.documentError = 'Erro ao validar CPF';
                } finally {
                    this.documentLoading = false;
                }
            },

            async fetchCNPJData(cnpj) {
            this.documentLoading = true;
            
            try {
                const response = await fetch(`/api/admin/cnpj/${cnpj}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();

                    this.companyName = data.razao_social || '';
                    this.companyTradeName = data.nome_fantasia || '';
                    this.clientName = data.nome_fantasia || data.razao_social || '';
                    
                    // Monta o endereço completo
                    const endereco = [
                        data.logradouro,
                        data.numero,
                        data.complemento
                    ].filter(Boolean).join(', ');
                    
                    this.clientAddress = endereco || '';
                    this.clientCity = data.municipio || '';
                    this.clientState = data.uf || '';
                    this.clientZipcode = data.cep || '';
                    
                    // Aplica máscara no CEP
                    this.cepMask();

                    this.documentSuccess = 'CNPJ válido e dados preenchidos!';
                } else {
                    this.documentError = 'CNPJ não encontrado';
                }
            } catch (error) {
                console.error('Erro:', error);
                this.documentError = 'Erro ao buscar dados do CNPJ';
            } finally {
                this.documentLoading = false;
            }
        },
            
            resetForm() {
                this.document = '';
                this.documentType = '';
                this.documentError = '';
                this.documentSuccess = '';
                this.clientName = '';
                this.clientPhone = '';
                this.clientEmail = '';
                this.clientAddress = '';
                this.clientCity = '';
                this.clientState = '';
                this.clientZipcode = '';
                this.companyName = '';
                this.companyTradeName = '';
                this.services = [];
                this.discount = 0;
                this.paymentConditions = '';
                this.validity = '';
                this.notes = '';
            }
        }
    }
</script>
@endsection