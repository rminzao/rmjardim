@extends('layouts.admin')

@section('title', 'Editor de Serviços')
@section('page-title', 'Serviços')
@section('page-description', 'Configure os serviços oferecidos')

@section('content')
<div class="space-y-6">
    
    <!-- Action Buttons -->
    <div class="flex gap-3 justify-between sticky top-0 bg-[hsl(60,30%,96%)] py-3 z-10">
        <button 
            @click="$refs.addServiceModal.classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-white transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Adicionar Serviço
        </button>
    </div>

    <!-- Services List -->
    <div class="space-y-4">
        @forelse($services as $index => $service)
        <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Icon Preview -->
                <div class="flex sm:flex-col items-center gap-3 sm:gap-2">
                    <span class="text-sm text-gray-500 hidden sm:block">
                        #{{ $index + 1 }}
                    </span>
                    <div class="w-14 h-14 rounded-xl bg-[hsl(142,50%,35%)]/10 flex items-center justify-center shrink-0">
                        {!! app(\App\Http\Controllers\Admin\ServiceController::class)->getIconSvg($service->icon) !!}
                    </div>
                </div>

                <!-- Service Info -->
                <div class="flex-1">
                    <h4 class="font-display text-lg font-semibold text-[hsl(150,30%,15%)] mb-2">
                        {{ $service->title }}
                    </h4>
                    <p class="text-gray-600 text-sm mb-3">
                        {{ $service->description }}
                    </p>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-600">
                            {{ $service->icon }}
                        </span>
                        @if($service->active)
                            <span class="px-2 py-1 rounded bg-green-100 text-green-700">
                                Ativo
                            </span>
                        @else
                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-600">
                                Inativo
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex sm:flex-col gap-2">
                    <button 
                        onclick="editService({{ json_encode($service) }})"
                        class="flex-1 sm:flex-none p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}" onsubmit="return confirm('Tem certeza?')" class="flex-1 sm:flex-none">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit"
                            class="w-full p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                        >
                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-xl border border-[hsl(90,20%,85%)]">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
            <p class="text-gray-500 mb-4">Nenhum serviço cadastrado</p>
            <button 
                @click="$refs.addServiceModal.classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-gray-50 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar primeiro serviço
            </button>
        </div>
        @endforelse
    </div>

    <!-- Icon Reference -->
    <div class="bg-gray-100 rounded-xl p-4">
        <p class="text-sm font-medium text-[hsl(150,30%,15%)] mb-3">Ícones disponíveis:</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @foreach($icons as $icon)
            <div class="flex items-center justify-center gap-2 px-3 py-3 bg-white rounded-lg border border-[hsl(90,20%,85%)] hover:border-[hsl(142,50%,35%)] transition-colors">
                <div class="w-7 h-7 shrink-0 text-[hsl(142,50%,35%)]">
                    {!! getIconSvg($icon) !!}
                </div>
                <span class="text-xs text-gray-600 truncate">{{ $icon }}</span>
            </div>
            @endforeach
        </div>
    </div>

<!-- Add Service Modal -->
<div 
    x-ref="addServiceModal"
    class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
    @click.self="$refs.addServiceModal.classList.add('hidden')"
>
    <div class="bg-white rounded-2xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto" @click.stop>
        <h3 class="font-display text-xl font-semibold text-[hsl(150,30%,15%)] mb-4">
            Adicionar Novo Serviço
        </h3>
        
        <form method="POST" action="{{ route('admin.services.store') }}" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Título</label>
                <input 
                    type="text" 
                    name="title"
                    required
                    placeholder="Ex: Manutenção de Jardins"
                    class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Ícone</label>
                <select 
                    name="icon"
                    required
                    class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                >
                    @foreach($icons as $icon)
                    <option value="{{ $icon }}">{{ $icon }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Descrição</label>
                <textarea 
                    name="description"
                    required
                    rows="3"
                    placeholder="Descreva o serviço..."
                    class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                ></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    name="active"
                    value="1"
                    checked
                    id="active_add"
                    class="rounded border-[hsl(90,20%,85%)] text-[hsl(142,50%,35%)] focus:ring-[hsl(142,50%,35%)]"
                >
                <label for="active_add" class="text-sm text-[hsl(150,30%,15%)]">Serviço ativo</label>
            </div>

            <div class="flex gap-3 mt-6">
                <button 
                    type="button"
                    @click="$refs.addServiceModal.classList.add('hidden')"
                    class="flex-1 px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-gray-50 transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-4 py-2 rounded-lg bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] transition-colors"
                >
                    Adicionar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Service Modal -->
<div 
    id="editServiceModal"
    class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4"
    @click.self="document.getElementById('editServiceModal').classList.add('hidden')"
>
    <div class="bg-white rounded-2xl p-6 w-full max-w-md max-h-[90vh] overflow-y-auto" @click.stop>
        <h3 class="font-display text-xl font-semibold text-[hsl(150,30%,15%)] mb-4">
            Editar Serviço
        </h3>
        
        <form id="editServiceForm" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')
            
            <div>
                <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Título</label>
                <input 
                    type="text" 
                    name="title"
                    id="edit_title"
                    required
                    class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Ícone</label>
                <select 
                    name="icon"
                    id="edit_icon"
                    required
                    class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                >
                    @foreach($icons as $icon)
                    <option value="{{ $icon }}">{{ $icon }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Descrição</label>
                <textarea 
                    name="description"
                    id="edit_description"
                    required
                    rows="3"
                    class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                ></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    name="active"
                    value="1"
                    id="edit_active"
                    class="rounded border-[hsl(90,20%,85%)] text-[hsl(142,50%,35%)] focus:ring-[hsl(142,50%,35%)]"
                >
                <label for="edit_active" class="text-sm text-[hsl(150,30%,15%)]">Serviço ativo</label>
            </div>

            <div class="flex gap-3 mt-6">
                <button 
                    type="button"
                    onclick="document.getElementById('editServiceModal').classList.add('hidden')"
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

<script>
function editService(service) {
    document.getElementById('editServiceForm').action = `/admin/services/${service.id}`;
    document.getElementById('edit_title').value = service.title;
    document.getElementById('edit_icon').value = service.icon;
    document.getElementById('edit_description').value = service.description;
    document.getElementById('edit_active').checked = service.active;
    document.getElementById('editServiceModal').classList.remove('hidden');
}
</script>

@php
    // Helper function for icons
    function getIconSvg($iconName) {
        $icons = [
            'TreeDeciduous' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>',
            'Scissors' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/></svg>',
            'Flower2' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'Droplets' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>',
            'Shovel' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
            'Sun' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
            'Leaf' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'Sprout' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>',
            'TreePine' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
            'Cloud' => '<svg class="w-7 h-7 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>',
        ];
        
        return $icons[$iconName] ?? $icons['Leaf'];
    }
@endphp
@endsection