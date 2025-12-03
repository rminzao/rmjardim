@extends('layouts.admin')

@section('title', 'Editor de Imagens')
@section('page-title', 'Imagens')
@section('page-description', 'Gerencie logo e portfólio')

@section('content')
<div class="space-y-8" x-data="{ showNewCard: false, newImagePreview: null, logoPreview: null, heroPreview: null }">
    
    <!-- Action Buttons -->
    <div class="flex gap-3 justify-end sticky top-0 bg-[hsl(60,30%,96%)] py-3 z-10">
        <button 
            onclick="document.getElementById('saveForm').submit()"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            Salvar Alterações
        </button>
    </div>

    <form id="saveForm" method="POST" action="{{ route('admin.gallery.updateSettings') }}" enctype="multipart/form-data">
        @csrf
        
        <!-- Logo Section -->
        <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] p-6">
            <h3 class="font-display text-lg font-semibold text-[hsl(150,30%,15%)] mb-4">
                Logo da Empresa
            </h3>
            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <div class="w-32 h-32 rounded-xl border-2 border-dashed border-[hsl(90,20%,85%)] flex items-center justify-center bg-[hsl(60,30%,96%)] overflow-hidden">
                    @if(isset($settings['logo_url']) && $settings['logo_url'])
                        <img x-show="!logoPreview" src="{{ asset('storage/' . $settings['logo_url']) }}" alt="Logo" class="w-full h-full object-contain">
                    @endif
                    <img x-show="logoPreview" :src="logoPreview" class="w-full h-full object-contain">
                    <svg x-show="!logoPreview && !{{ isset($settings['logo_url']) && $settings['logo_url'] ? 'true' : 'false' }}" class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1 space-y-3">
                    <p class="text-sm text-gray-500">
                        Recomendado: PNG ou SVG com fundo transparente, mínimo 200x200px
                    </p>
                    <label class="cursor-pointer inline-block">
                        <input
                            type="file"
                            name="logo"
                            accept="image/*"
                            class="hidden"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { logoPreview = e.target.result; }; reader.readAsDataURL(file); }"
                        >
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Carregar Logo
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Hero Image Section -->
        <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] p-6">
            <h3 class="font-display text-lg font-semibold text-[hsl(150,30%,15%)] mb-4">
                Imagem de Fundo (Hero)
            </h3>
            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <div class="w-48 h-32 rounded-xl border-2 border-dashed border-[hsl(90,20%,85%)] flex items-center justify-center bg-[hsl(60,30%,96%)] overflow-hidden">
                    @if(isset($settings['hero_image_url']) && $settings['hero_image_url'])
                        <img x-show="!heroPreview" src="{{ asset('storage/' . $settings['hero_image_url']) }}" alt="Hero" class="w-full h-full object-cover">
                    @endif
                    <img x-show="heroPreview" :src="heroPreview" class="w-full h-full object-cover">
                    <svg x-show="!heroPreview && !{{ isset($settings['hero_image_url']) && $settings['hero_image_url'] ? 'true' : 'false' }}" class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1 space-y-3">
                    <p class="text-sm text-gray-500">
                        Recomendado: Imagem horizontal de alta qualidade, mínimo 1920x1080px
                    </p>
                    <label class="cursor-pointer inline-block">
                        <input
                            type="file"
                            name="hero_image"
                            accept="image/*"
                            class="hidden"
                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { heroPreview = e.target.result; }; reader.readAsDataURL(file); }"
                        >
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Carregar Imagem
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Portfolio Images Grid -->
    <div class="bg-white rounded-xl border border-[hsl(90,20%,85%)] p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-display text-lg font-semibold text-[hsl(150,30%,15%)]">
                Portfólio de Trabalhos
            </h3>
            <button 
                @click="showNewCard = true; newImagePreview = null"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[hsl(142,50%,35%)] text-[hsl(142,50%,35%)] hover:bg-[hsl(142,50%,35%)] hover:text-white transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($images as $image)
            <div class="bg-[hsl(60,30%,96%)] rounded-xl border border-[hsl(90,20%,85%)] overflow-hidden">
                <!-- Image Preview -->
                <div class="relative aspect-square bg-gray-200">
                    @if($image->image_path)
                        <img 
                            src="{{ asset('storage/' . $image->image_path) }}" 
                            alt="{{ $image->title }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="absolute top-2 right-2">
                        <span class="px-2 py-1 bg-white/90 backdrop-blur rounded text-xs font-medium">
                            #{{ $image->order }}
                        </span>
                    </div>
                </div>
                
                <!-- Card Content -->
                <div class="p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                        <input 
                            type="text" 
                            value="{{ $image->title }}"
                            onchange="updateImage({{ $image->id }}, 'title', this.value)"
                            class="w-full px-2 py-1.5 text-sm rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                        >
                    </div>

                    <div class="flex gap-2">
                        <label class="flex-1 cursor-pointer">
                            <input
                                type="file"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="hidden"
                                onchange="uploadImage({{ $image->id }}, this)"
                            >
                            <div class="w-full inline-flex items-center justify-center gap-1 px-3 py-2 text-sm rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-gray-50 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Imagem
                            </div>
                        </label>
                        
                        <button 
                            onclick="deleteImage({{ $image->id }})"
                            class="inline-flex items-center justify-center px-3 py-2 text-sm rounded-lg border border-[hsl(90,20%,85%)] text-red-600 hover:bg-red-50 transition-colors"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Add New Card -->
            <div x-show="showNewCard" class="bg-[hsl(60,30%,96%)] rounded-xl border-2 border-dashed border-[hsl(142,50%,35%)] overflow-hidden">
                <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Image Upload Area -->
                    <div class="relative aspect-square bg-gray-100">
                        <label class="w-full h-full flex flex-col items-center justify-center cursor-pointer hover:bg-gray-200 transition-colors">
                            <input
                                type="file"
                                name="image"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                required
                                class="hidden"
                                @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { newImagePreview = e.target.result; }; reader.readAsDataURL(file); }"
                            >
                            <svg x-show="!newImagePreview" class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span x-show="!newImagePreview" class="text-sm text-gray-500">Carregar Imagem</span>
                        </label>
                        <img x-show="newImagePreview" :src="newImagePreview" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    
                    <!-- Card Content -->
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Título</label>
                            <input 
                                type="text" 
                                name="title"
                                placeholder="Nome do projeto"
                                required
                                class="w-full px-2 py-1.5 text-sm rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                            >
                        </div>

                        <input type="hidden" name="active" value="1">

                        <div class="flex gap-2">
                            <button 
                                type="submit"
                                class="flex-1 px-3 py-2 text-sm rounded-lg bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] transition-colors"
                            >
                                Salvar
                            </button>
                            <button 
                                type="button"
                                @click="showNewCard = false; newImagePreview = null"
                                class="px-3 py-2 text-sm rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-gray-50 transition-colors"
                            >
                                Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($images->count() === 0)
        <div x-show="!showNewCard" class="text-center py-12">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-500 mb-4">Nenhuma imagem no portfólio</p>
            <button 
                @click="showNewCard = true; newImagePreview = null"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-gray-50 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar primeira imagem
            </button>
        </div>
        @endif
    </div>
</div>

<script>
async function updateImage(id, field, value) {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PATCH');
    formData.append(field, value);
    
    try {
        await fetch(`/admin/gallery/${id}`, {
            method: 'POST',
            body: formData
        });
        console.log('Atualizado com sucesso');
    } catch (error) {
        console.error('Erro ao atualizar:', error);
    }
}

async function uploadImage(id, input) {
    const file = input.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PATCH');
    formData.append('image', file);
    
    try {
        const response = await fetch(`/admin/gallery/${id}`, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Erro ao fazer upload:', error);
    }
}

async function deleteImage(id) {
    if (!confirm('Tem certeza que deseja excluir esta imagem?')) return;
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'DELETE');
    
    try {
        const response = await fetch(`/admin/gallery/${id}`, {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Erro ao excluir:', error);
    }
}
</script>
@endsection