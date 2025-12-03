@extends('layouts.admin')

@section('title', 'Clientes')
@section('page-title', 'Clientes')
@section('page-description', 'Gerencie solicitações de orçamento')

@section('content')
<div class="space-y-6">
    
    <!-- Filters & Search -->
    <div class="flex flex-col gap-4">
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar cliente..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                >
            </div>
            
            <!-- Filter Buttons -->
            <div class="flex gap-2 flex-wrap">
                <button 
                    type="submit"
                    name="filter"
                    value="all"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('filter') || request('filter') === 'all' ? 'bg-[hsl(142,50%,35%)] text-white' : 'bg-white text-gray-600 border border-[hsl(90,20%,85%)]' }}"
                >
                    Todos ({{ $stats['total_contacts'] }})
                </button>
                <button 
                    type="submit"
                    name="filter"
                    value="pending"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('filter') === 'pending' ? 'bg-[hsl(142,50%,35%)] text-white' : 'bg-white text-gray-600 border border-[hsl(90,20%,85%)]' }}"
                >
                    Pendentes ({{ $stats['pending_contacts'] }})
                </button>
                <button 
                    type="submit"
                    name="filter"
                    value="hired"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('filter') === 'hired' ? 'bg-[hsl(142,50%,35%)] text-white' : 'bg-white text-gray-600 border border-[hsl(90,20%,85%)]' }}"
                >
                    Contratados ({{ $stats['hired_clients'] }})
                </button>
                <button 
                    type="submit"
                    name="filter"
                    value="maintenance"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-1 {{ request('filter') === 'maintenance' ? 'bg-amber-500 text-white' : 'bg-white text-gray-600 border border-[hsl(90,20%,85%)]' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Manutenção ({{ $stats['needs_maintenance'] }})
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-[hsl(90,20%,85%)]">
            <p class="text-gray-500 text-sm">Total de Clientes</p>
            <p class="text-2xl font-bold text-[hsl(150,30%,15%)]">{{ $stats['total_contacts'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-[hsl(90,20%,85%)]">
            <p class="text-gray-500 text-sm">Aguardando</p>
            <p class="text-2xl font-bold text-amber-500">{{ $stats['pending_contacts'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-[hsl(90,20%,85%)]">
            <p class="text-gray-500 text-sm">Contratados</p>
            <p class="text-2xl font-bold text-[hsl(142,50%,35%)]">{{ $stats['hired_clients'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-[hsl(90,20%,85%)]">
            <p class="text-gray-500 text-sm">Precisam Manutenção</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['needs_maintenance'] }}</p>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-[hsl(90,20%,85%)]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[hsl(90,20%,85%)]">
                    @forelse($clients as $client)
                    <tr class="{{ ($client->needs_maintenance ?? false) ? 'bg-amber-50' : '' }}">
                        <!-- Cliente -->
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-[hsl(150,30%,15%)]">{{ $client->name }}</p>
                                <p class="text-xs text-gray-400">Enviado: {{ \Carbon\Carbon::parse($client->created_at)->format('d/m/Y') }}</p>
                            </div>
                        </td>
                        
                        <!-- Contato -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-[hsl(150,30%,15%)]">{{ $client->phone }}</p>
                        </td>
                        
                        <!-- Descrição -->
                        <td class="px-6 py-4">
                            <p class="text-gray-500 max-w-xs">{{ $client->message }}</p>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1">
                                    @if($client->whatsapp_sent)
                                        <svg class="w-4 h-4 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                    <span class="text-xs text-gray-600">Msg</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    @if($client->status === 'hired')
                                        <svg class="w-4 h-4 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xs text-gray-600">Contratado</span>
                                    @else
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xs text-gray-600">Pendente</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <!-- Ações -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-2">
                                <button 
                                    onclick="editClient({{ json_encode($client) }})"
                                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('admin.clients.destroy', $client->id) }}" onsubmit="return confirm('Tem certeza?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Nenhum cliente encontrado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($clients->hasPages())
        <div class="px-6 py-4 border-t border-[hsl(90,20%,85%)]">
            {{ $clients->links() }}
        </div>
        @endif
    </div>

    <!-- Edit Modal -->
    <div 
        id="editModal"
        class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
        onclick="if(event.target === this) this.classList.add('hidden')"
    >
        <div class="bg-white rounded-2xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <h3 class="font-display text-xl font-semibold text-[hsl(150,30%,15%)] mb-4">
                Editar Cliente
            </h3>
            
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Nome</label>
                    <input 
                        type="text" 
                        name="name"
                        id="edit_name"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Telefone</label>
                    <input 
                        type="text" 
                        name="phone"
                        id="edit_phone"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Descrição</label>
                    <textarea 
                        name="message"
                        id="edit_message"
                        required
                        rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Status</label>
                    <select 
                        name="status"
                        id="edit_status"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                        <option value="new">Novo</option>
                        <option value="contacted">Contatado</option>
                        <option value="hired">Contratado</option>
                        <option value="canceled">Cancelado</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <input 
                        type="checkbox" 
                        name="whatsapp_sent"
                        id="edit_whatsapp_sent"
                        value="1"
                        class="rounded border-[hsl(90,20%,85%)] text-[hsl(142,50%,35%)] focus:ring-[hsl(142,50%,35%)]"
                    >
                    <label for="edit_whatsapp_sent" class="text-sm text-[hsl(150,30%,15%)]">Mensagem Enviada</label>
                </div>

                <div id="maintenanceFields" style="display: none;">
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">
                        Prazo para Manutenção (dias)
                    </label>
                    <input 
                        type="number" 
                        name="maintenance_days"
                        id="edit_maintenance_days"
                        min="1"
                        value="30"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Observações</label>
                    <textarea 
                        name="notes"
                        id="edit_notes"
                        rows="2"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                    ></textarea>
                </div>

                <div class="flex gap-3 mt-6">
                    <button 
                        type="button"
                        onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-gray-50 transition-colors"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-4 py-2 rounded-lg bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] transition-colors"
                    >
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editClient(client) {
    document.getElementById('editForm').action = `/admin/clients/${client.id}`;
    document.getElementById('edit_name').value = client.name;
    document.getElementById('edit_phone').value = client.phone;
    document.getElementById('edit_message').value = client.message;
    document.getElementById('edit_status').value = client.status;
    document.getElementById('edit_whatsapp_sent').checked = client.whatsapp_sent;
    document.getElementById('edit_maintenance_days').value = client.maintenance_days || 30;
    document.getElementById('edit_notes').value = client.notes || '';
    
    // Show/hide maintenance fields
    const maintenanceFields = document.getElementById('maintenanceFields');
    if (client.status === 'hired') {
        maintenanceFields.style.display = 'block';
    } else {
        maintenanceFields.style.display = 'none';
    }
    
    // Add listener to status change
    document.getElementById('edit_status').addEventListener('change', function() {
        if (this.value === 'hired') {
            maintenanceFields.style.display = 'block';
        } else {
            maintenanceFields.style.display = 'none';
        }
    });
    
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection