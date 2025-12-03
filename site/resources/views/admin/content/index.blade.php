@extends('layouts.admin')

@section('title', 'Editor de Conteúdo')
@section('page-title', 'Conteúdo')
@section('page-description', 'Edite os textos do site')

@section('content')
<div class="space-y-6" x-data="contentEditor()">
    
    <!-- Action Buttons -->
    <div class="flex gap-3 justify-end sticky top-0 bg-[hsl(60,30%,96%)] py-3 z-10">
        <button 
            @click="window.location.reload()"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-[hsl(90,20%,85%)] text-gray-600 hover:bg-white transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Descartar
        </button>
        <button 
            @click="$refs.saveForm.submit()"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] transition-colors"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
            </svg>
            Salvar Alterações
        </button>
    </div>

    <form method="POST" action="{{ route('admin.content.update') }}" x-ref="saveForm" class="space-y-4">
        @csrf

        <!-- Header / Logo -->
        <div class="border border-[hsl(90,20%,85%)] rounded-xl overflow-hidden">
            <button 
                type="button"
                @click="sections.header = !sections.header"
                class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-gray-50 transition-colors"
            >
                <span class="font-medium text-[hsl(150,30%,15%)]">🏠 Header / Logo</span>
                <svg 
                    class="w-5 h-5 text-gray-400 transition-transform"
                    :class="sections.header ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="sections.header" x-collapse class="p-4 space-y-4 bg-[hsl(60,30%,96%)]">
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Texto da Logo</label>
                    <input 
                        type="text" 
                        name="logo_text"
                        value="{{ $settings['logo_text'] ?? 'RM Jardim' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="border border-[hsl(90,20%,85%)] rounded-xl overflow-hidden">
            <button 
                type="button"
                @click="sections.hero = !sections.hero"
                class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-gray-50 transition-colors"
            >
                <span class="font-medium text-[hsl(150,30%,15%)]">🌟 Hero (Seção Principal)</span>
                <svg 
                    class="w-5 h-5 text-gray-400 transition-transform"
                    :class="sections.hero ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="sections.hero" x-collapse class="p-4 space-y-4 bg-[hsl(60,30%,96%)]">
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Badge/Tag</label>
                    <input 
                        type="text" 
                        name="hero_badge"
                        value="{{ $settings['hero_badge'] ?? '🌿 Transformamos seu espaço em um paraíso verde' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Título Principal</label>
                    <input 
                        type="text" 
                        name="hero_title"
                        value="{{ $settings['hero_title'] ?? 'Jardins que' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Texto em Destaque</label>
                    <input 
                        type="text" 
                        name="hero_title_highlight"
                        value="{{ $settings['hero_title_highlight'] ?? 'Encantam' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Descrição</label>
                    <textarea 
                        name="hero_description"
                        rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                    >{{ $settings['hero_description'] ?? 'Especialistas em paisagismo, manutenção de jardins e projetos personalizados. Deixe a natureza fazer parte do seu dia a dia.' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Botão Primário</label>
                    <input 
                        type="text" 
                        name="hero_button_primary"
                        value="{{ $settings['hero_button_primary'] ?? 'Solicitar Orçamento Grátis' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Botão Secundário</label>
                    <input 
                        type="text" 
                        name="hero_button_secondary"
                        value="{{ $settings['hero_button_secondary'] ?? 'Ver Trabalhos' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
            </div>
        </div>

        <!-- Services -->
        <div class="border border-[hsl(90,20%,85%)] rounded-xl overflow-hidden">
            <button 
                type="button"
                @click="sections.services = !sections.services"
                class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-gray-50 transition-colors"
            >
                <span class="font-medium text-[hsl(150,30%,15%)]">🔧 Serviços</span>
                <svg 
                    class="w-5 h-5 text-gray-400 transition-transform"
                    :class="sections.services ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="sections.services" x-collapse class="p-4 space-y-4 bg-[hsl(60,30%,96%)]">
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Tag</label>
                    <input 
                        type="text" 
                        name="services_tag"
                        value="{{ $settings['services_tag'] ?? 'Nossos Serviços' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Título</label>
                    <input 
                        type="text" 
                        name="services_title"
                        value="{{ $settings['services_title'] ?? 'O que oferecemos' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Descrição</label>
                    <textarea 
                        name="services_description"
                        rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                    >{{ $settings['services_description'] ?? 'Soluções completas em jardinagem e paisagismo.' }}</textarea>
                </div>
                <p class="text-sm text-gray-500">
                    Para editar os serviços individualmente, vá para a aba "Serviços"
                </p>
            </div>
        </div>

        <!-- Portfolio -->
        <div class="border border-[hsl(90,20%,85%)] rounded-xl overflow-hidden">
            <button 
                type="button"
                @click="sections.portfolio = !sections.portfolio"
                class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-gray-50 transition-colors"
            >
                <span class="font-medium text-[hsl(150,30%,15%)]">📸 Portfólio</span>
                <svg 
                    class="w-5 h-5 text-gray-400 transition-transform"
                    :class="sections.portfolio ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="sections.portfolio" x-collapse class="p-4 space-y-4 bg-[hsl(60,30%,96%)]">
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Tag</label>
                    <input 
                        type="text" 
                        name="portfolio_tag"
                        value="{{ $settings['portfolio_tag'] ?? 'Portfólio' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Título</label>
                    <input 
                        type="text" 
                        name="portfolio_title"
                        value="{{ $settings['portfolio_title'] ?? 'Trabalhos Realizados' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Descrição</label>
                    <textarea 
                        name="portfolio_description"
                        rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                    >{{ $settings['portfolio_description'] ?? 'Confira alguns dos nossos projetos.' }}</textarea>
                </div>
                <p class="text-sm text-gray-500">
                    Para editar as imagens do portfólio, vá para a aba "Imagens"
                </p>
            </div>
        </div>

        <!-- Contact -->
        <div class="border border-[hsl(90,20%,85%)] rounded-xl overflow-hidden">
            <button 
                type="button"
                @click="sections.contact = !sections.contact"
                class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-gray-50 transition-colors"
            >
                <span class="font-medium text-[hsl(150,30%,15%)]">📝 Formulário de Contato</span>
                <svg 
                    class="w-5 h-5 text-gray-400 transition-transform"
                    :class="sections.contact ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="sections.contact" x-collapse class="p-4 space-y-4 bg-[hsl(60,30%,96%)]">
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Tag</label>
                    <input 
                        type="text" 
                        name="contact_tag"
                        value="{{ $settings['contact_tag'] ?? 'Orçamento' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Título</label>
                    <input 
                        type="text" 
                        name="contact_title"
                        value="{{ $settings['contact_title'] ?? 'Solicite seu Orçamento' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Descrição</label>
                    <textarea 
                        name="contact_description"
                        rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                    >{{ $settings['contact_description'] ?? 'Preencha o formulário abaixo e entraremos em contato em breve para conversar sobre seu projeto.' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="border border-[hsl(90,20%,85%)] rounded-xl overflow-hidden">
            <button 
                type="button"
                @click="sections.footer = !sections.footer"
                class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-gray-50 transition-colors"
            >
                <span class="font-medium text-[hsl(150,30%,15%)]">📍 Footer</span>
                <svg 
                    class="w-5 h-5 text-gray-400 transition-transform"
                    :class="sections.footer ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="sections.footer" x-collapse class="p-4 space-y-4 bg-[hsl(60,30%,96%)]">
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Descrição da Empresa</label>
                    <textarea 
                        name="footer_description"
                        rows="3"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] resize-none"
                    >{{ $settings['footer_description'] ?? 'Transformando espaços em jardins dos sonhos.' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Telefone</label>
                    <input 
                        type="text" 
                        name="footer_phone"
                        value="{{ $settings['footer_phone'] ?? '(00) 00000-0000' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Email</label>
                    <input 
                        type="email" 
                        name="footer_email"
                        value="{{ $settings['footer_email'] ?? 'contato@email.com' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Endereço</label>
                    <input 
                        type="text" 
                        name="footer_address"
                        value="{{ $settings['footer_address'] ?? 'Sua cidade, Estado' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">Nome para Copyright</label>
                    <input 
                        type="text" 
                        name="footer_copyright"
                        value="{{ $settings['footer_copyright'] ?? 'RM Jardim' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">URL Instagram</label>
                    <input 
                        type="url" 
                        name="instagram_url"
                        value="{{ $settings['instagram_url'] ?? '#' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-1">URL Facebook</label>
                    <input 
                        type="url" 
                        name="facebook_url"
                        value="{{ $settings['facebook_url'] ?? '#' }}"
                        class="w-full px-3 py-2 rounded-lg border border-[hsl(90,20%,85%)] bg-white text-[hsl(150,30%,15%)] focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)]"
                    >
                </div>
            </div>
        </div>

    </form>
</div>

<script>
function contentEditor() {
    return {
        sections: {
            header: true,
            hero: false,
            services: false,
            portfolio: false,
            contact: false,
            footer: false
        }
    }
}
</script>
@endsection