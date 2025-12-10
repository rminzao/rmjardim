@extends('layouts.admin')

@section('title', 'Gerenciador WhatsApp')
@section('page-title', 'WhatsApp')
@section('page-description', 'Gerencie a conexão da API WhatsApp')

@section('content')
<div class="space-y-6">
    <div x-data="whatsappManager()" x-init="init()">
        
        <!-- Card de Status -->
        <div class="bg-white rounded-xl border transition-all duration-300"
             :class="status.connected ? 'border-green-500/50 bg-green-50/30' : 'border-red-500/50 bg-red-50/30'">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <!-- Ícone dinâmico -->
                        <svg x-show="status.connected" class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                        </svg>
                        <svg x-show="!status.connected" class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-900">Status da Conexão</h2>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium"
                          :class="status.connected ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                          x-text="status.connected ? 'Conectado' : 'Desconectado'">
                    </span>
                </div>
                
                <!-- Info Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium" :class="status.connected ? 'text-green-600' : 'text-red-600'" x-text="status.connected ? 'Online' : 'Offline'"></span>
                    </div>
                    
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-gray-600">Tempo online:</span>
                        <span class="font-medium text-gray-900" x-text="formatUptime(status.uptime)"></span>
                    </div>
                    
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <span class="text-gray-600">Mensagens:</span>
                        <span class="font-medium text-gray-900" x-text="status.messagesSent || 0"></span>
                    </div>
                    
                    <button @click="fetchStatus()" 
                            :disabled="loading.status"
                            class="inline-flex items-center justify-center gap-2 px-3 py-1.5 text-sm rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition-colors disabled:opacity-50">
                        <svg class="w-4 h-4" :class="{'animate-spin': loading.status}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Atualizar
                    </button>
                </div>
            </div>
        </div>

        <!-- Grid: QR Code + Ações -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Card QR Code -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">QR Code</h3>
                    </div>
                </div>
                <div class="p-6 flex items-center justify-center min-h-[300px]">
                    <!-- Conectado -->
                    <div x-show="status.connected" class="text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                        </svg>
                        <p class="font-medium text-gray-900">WhatsApp conectado!</p>
                        <p class="text-sm mt-1">Não é necessário escanear QR Code</p>
                    </div>

                    <!-- QR Code disponível -->
                    <div x-show="!status.connected && qrCode" class="text-center">
                        <img :src="qrCode" alt="QR Code WhatsApp" class="w-64 h-64 mx-auto border-4 border-gray-200 rounded-lg shadow-lg">
                        <p class="mt-4 text-sm text-gray-600">Escaneie com o WhatsApp</p>
                    </div>

                    <!-- Sem QR Code -->
                    <div x-show="!status.connected && !qrCode" class="text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <p class="font-medium">QR Code não disponível</p>
                        <p class="text-sm mt-1">Clique em "Conectar" para gerar</p>
                    </div>
                </div>
            </div>

            <!-- Card Ações -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ações</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button @click="handleConnect()"
                            :disabled="status.connected || loading.connect"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="status.connected ? 'bg-gray-100 text-gray-400 border border-gray-200' : 'bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)]'">
                        <svg x-show="!loading.connect" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path>
                        </svg>
                        <svg x-show="loading.connect" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Conectar
                    </button>

                    <button @click="handleDisconnect()"
                            :disabled="!status.connected || loading.disconnect"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg x-show="!loading.disconnect" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
                        </svg>
                        <svg x-show="loading.disconnect" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Desconectar
                    </button>

                    <button @click="handleRestart()"
                            :disabled="loading.restart"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border-2 border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg x-show="!loading.restart" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <svg x-show="loading.restart" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Reiniciar
                    </button>
                </div>
            </div>
        </div>

        <!-- Card de Logs -->
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Logs em Tempo Real</h3>
                </div>
                <button @click="fetchLogs()" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Atualizar
                </button>
            </div>
            <div class="p-6">
                <div class="h-[400px] overflow-y-auto bg-gray-50 rounded-lg border border-gray-200 p-4 font-mono text-xs flex flex-col-reverse" x-ref="logsContainer">
                    <template x-if="logs.length > 0">
                        <div class="space-y-1">
                            <template x-for="(log, index) in logs" :key="index">
                                <div class="whitespace-pre-wrap" x-text="log"></div>
                            </template>
                        </div>
                    </template>
                    <template x-if="logs.length === 0">
                        <div class="flex items-center justify-center h-full text-gray-400">
                            <p>Nenhum log disponível</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function whatsappManager() {
    return {
        status: { connected: false, uptime: 0, messagesSent: 0 },
        qrCode: null,
        logs: [],
        loading: {
            status: false,
            connect: false,
            disconnect: false,
            restart: false
        },
        
        async init() {
            await this.fetchStatus();
            await this.fetchLogs();
            
            // Polling
            setInterval(() => this.fetchStatus(), 10000); // 10s
            setInterval(() => this.fetchLogs(), 5000);    // 5s
        },
        
        async fetchStatus() {
            this.loading.status = true;
            try {
                const response = await fetch('{{ route("admin.whatsapp.status") }}');
                if (response.ok) {
                    this.status = await response.json();
                    
                    if (!this.status.connected) {
                        await this.fetchQrCode();
                    } else {
                        this.qrCode = null;
                    }
                }
            } catch (error) {
                console.error('Erro ao buscar status:', error);
            } finally {
                this.loading.status = false;
            }
        },
        
        async fetchQrCode() {
            try {
                const response = await fetch('{{ route("admin.whatsapp.qrcode") }}');
                if (response.ok) {
                    const data = await response.json();
                    this.qrCode = data.qrcode;
                }
            } catch (error) {
                console.error('Erro ao buscar QR Code:', error);
            }
        },
        
        async fetchLogs() {
            try {
                const response = await fetch('{{ route("admin.whatsapp.logs") }}');
                if (response.ok) {
                    const data = await response.json();
                    this.logs = data.logs || [];
                }
            } catch (error) {
                console.error('Erro ao buscar logs:', error);
            }
        },
        
        async handleConnect() {
            this.loading.connect = true;
            try {
                const response = await fetch('{{ route("admin.whatsapp.connect") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    alert('Conectando... Aguarde o QR Code aparecer');
                    setTimeout(() => this.fetchQrCode(), 2000);
                }
            } catch (error) {
                alert('Erro ao conectar');
            } finally {
                this.loading.connect = false;
            }
        },
        
        async handleDisconnect() {
            if (!confirm('Deseja realmente desconectar?')) return;
            
            this.loading.disconnect = true;
            try {
                const response = await fetch('{{ route("admin.whatsapp.disconnect") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    alert('Desconectado com sucesso!');
                    this.status.connected = false;
                    this.qrCode = null;
                }
            } catch (error) {
                alert('Erro ao desconectar');
            } finally {
                this.loading.disconnect = false;
            }
        },
        
        async handleRestart() {
            if (!confirm('Deseja reiniciar a conexão? Você precisará escanear o QR Code novamente.')) return;
            
            this.loading.restart = true;
            try {
                const response = await fetch('{{ route("admin.whatsapp.restart") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (response.ok) {
                    alert('Reiniciando... Aguarde alguns segundos');
                    setTimeout(() => this.fetchStatus(), 3000);
                }
            } catch (error) {
                alert('Erro ao reiniciar');
            } finally {
                this.loading.restart = false;
            }
        },
        
        formatUptime(seconds) {
            if (!seconds) return '0s';
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            if (hours > 0) return `${hours}h ${minutes}m`;
            if (minutes > 0) return `${minutes}m ${secs}s`;
            return `${secs}s`;
        }
    }
}
</script>
@endsection