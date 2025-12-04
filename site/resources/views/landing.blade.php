<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['logo_text'] ?? 'RM Jardim' }} | Paisagismo e Jardinagem</title>
    <meta name="description" content="Serviços profissionais de jardinagem, paisagismo e manutenção de jardins.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-display { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
        
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-fade-up { animation: fade-up 0.8s ease-out forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>
<body class="bg-[hsl(60,30%,96%)] text-[hsl(150,30%,15%)] font-sans antialiased">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-[hsl(90,20%,85%)]">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    @if(isset($settings['logo_url']) && $settings['logo_url'])
                        <img src="{{ asset('storage/' . $settings['logo_url']) }}" alt="Logo" class="w-24 md:w-32 h-auto object-contain">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-[hsl(142,50%,35%)]/10 border-2 border-dashed border-[hsl(142,50%,35%)] flex items-center justify-center">
                            <svg class="w-6 h-6 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <span class="font-display text-xl font-semibold">{{ $settings['logo_text'] ?? 'RM Jardim' }}</span>
                    @endif
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="#inicio" class="text-gray-600 hover:text-[hsl(142,50%,35%)] transition-colors font-medium">Início</a>
                    <a href="#servicos" class="text-gray-600 hover:text-[hsl(142,50%,35%)] transition-colors font-medium">Serviços</a>
                    <a href="#portfolio" class="text-gray-600 hover:text-[hsl(142,50%,35%)] transition-colors font-medium">Portfólio</a>
                    <a href="#orcamento" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-semibold transition-all duration-300 bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] hover:shadow-lg hover:scale-105 h-10 px-4 py-2">
                        {{ $settings['hero_button_primary'] ?? 'Solicitar Orçamento' }}
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <nav id="mobile-menu" class="hidden md:hidden mt-4 pb-4 flex flex-col gap-4">
                <a href="#inicio" class="text-gray-600 hover:text-[hsl(142,50%,35%)] transition-colors font-medium text-left py-2">Início</a>
                <a href="#servicos" class="text-gray-600 hover:text-[hsl(142,50%,35%)] transition-colors font-medium text-left py-2">Serviços</a>
                <a href="#portfolio" class="text-gray-600 hover:text-[hsl(142,50%,35%)] transition-colors font-medium text-left py-2">Portfólio</a>
                <a href="#orcamento" class="inline-flex items-center justify-center gap-2 rounded-lg text-sm font-semibold bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] h-10 px-4 py-2 w-full">
                    {{ $settings['hero_button_primary'] ?? 'Solicitar Orçamento' }}
                </a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="inicio" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ isset($settings['hero_image_url']) && $settings['hero_image_url'] ? asset('storage/' . $settings['hero_image_url']) : asset('images/hero-bg.jpg') }}');">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 container mx-auto px-4 text-center pt-20">
            <div class="max-w-3xl mx-auto">
                <span class="inline-block px-4 py-2 rounded-full bg-[hsl(142,50%,35%)]/20 text-white text-sm font-medium mb-6 animate-fade-up backdrop-blur-sm border border-white/20">
                    {{ $settings['hero_badge'] ?? '🌿 Transformamos seu espaço em um paraíso verde' }}
                </span>
                
                <h1 class="font-display text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 animate-fade-up delay-100">
                    {{ $settings['hero_title'] ?? 'Jardins que' }}
                    <span class="block text-[hsl(100,25%,70%)]">{{ $settings['hero_title_highlight'] ?? 'Encantam' }}</span>
                </h1>
                
                <p class="text-lg md:text-xl text-white/90 mb-10 max-w-2xl mx-auto animate-fade-up delay-200">
                    {{ $settings['hero_description'] ?? 'Especialistas em paisagismo e manutenção de jardins.' }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-up delay-300">
                    <a href="#orcamento" class="inline-flex items-center justify-center gap-2 rounded-lg text-lg font-semibold bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] hover:shadow-lg hover:scale-105 transition-all h-14 px-10">
                        {{ $settings['hero_button_primary'] ?? 'Solicitar Orçamento Grátis' }}
                    </a>
                    <a href="#portfolio" class="inline-flex items-center justify-center gap-2 rounded-lg text-lg font-semibold border-2 border-white/50 text-white hover:bg-white/10 transition-all h-14 px-10">
                        {{ $settings['hero_button_secondary'] ?? 'Ver Trabalhos' }}
                    </a>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-float">
                <a href="#servicos" class="flex flex-col items-center text-white/70 hover:text-white transition-colors">
                    <span class="text-sm mb-2">Saiba mais</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="servicos" class="py-24 bg-[hsl(60,20%,98%)]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 rounded-full bg-[hsl(142,50%,35%)]/10 text-[hsl(142,50%,35%)] text-sm font-medium mb-4">
                    {{ $settings['services_tag'] ?? 'Nossos Serviços' }}
                </span>
                <h2 class="font-display text-3xl md:text-5xl font-bold mb-4">
                    {{ $settings['services_title'] ?? 'O que oferecemos' }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    {{ $settings['services_description'] ?? 'Soluções completas em jardinagem e paisagismo.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $index => $service)
                <div class="group p-8 rounded-2xl bg-white border border-[hsl(90,20%,85%)] hover:border-[hsl(142,50%,35%)]/50 hover:shadow-lg transition-all duration-300 animate-fade-up" style="animation-delay: {{ $index * 100 }}ms">
                    <div class="w-14 h-14 rounded-xl bg-[hsl(142,50%,35%)]/10 flex items-center justify-center mb-6 group-hover:bg-[hsl(142,50%,35%)] group-hover:scale-110 transition-all duration-300">
                        <div class="w-7 h-7 text-[hsl(142,50%,35%)] group-hover:text-white transition-colors">
                            {!! app(\App\Http\Controllers\Admin\ServiceController::class)->getIconSvg($service->icon) !!}
                        </div>
                    </div>
                    <h3 class="font-display text-xl font-semibold mb-3">{{ $service->title }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $service->description }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

   <!-- Portfolio Section -->
    <section id="portfolio" class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 rounded-full bg-[hsl(142,50%,35%)]/10 text-[hsl(142,50%,35%)] text-sm font-medium mb-4">
                    {{ $settings['portfolio_tag'] ?? 'Portfólio' }}
                </span>
                <h2 class="font-display text-3xl md:text-5xl font-bold mb-4">
                    {{ $settings['portfolio_title'] ?? 'Trabalhos Realizados' }}
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    {{ $settings['portfolio_description'] ?? 'Confira alguns dos nossos projetos.' }}
                </p>
            </div>

            @if(count($servicePortfolios) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($servicePortfolios as $index => $portfolio)
                <div class="group relative overflow-hidden rounded-2xl cursor-pointer animate-fade-up" 
                     style="animation-delay: {{ $index * 100 }}ms"
                     x-data="carousel{{ $index }}()"
                     @mouseenter="stopAutoplay()"
                     @mouseleave="startAutoplay()">
                    
                    <!-- Carousel Container -->
                    <div class="aspect-square relative">
                        <!-- Images -->
                        @foreach($portfolio['images'] as $imgIndex => $image)
                        <div x-show="currentSlide === {{ $imgIndex }}" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="absolute inset-0">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $image->title }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        @endforeach

                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Service Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="inline-block px-3 py-1 rounded-full bg-[hsl(142,50%,35%)]/90 text-white text-xs font-medium backdrop-blur">
                                {{ $portfolio['service']->title }}
                            </span>
                        </div>

                        <!-- Image Counter -->
                        @if($portfolio['images']->count() > 1)
                        <div class="absolute top-4 right-4">
                            <span class="inline-block px-3 py-1 rounded-full bg-black/50 text-white text-xs font-medium backdrop-blur">
                                <span x-text="currentSlide + 1"></span>/{{ $portfolio['images']->count() }}
                            </span>
                        </div>
                        @endif

                        <!-- Title on Hover -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <h3 class="font-display text-xl font-semibold text-white">
                                <span x-text="images[currentSlide].title"></span>
                            </h3>
                        </div>

                        <!-- Navigation Buttons (only show if more than 1 image) -->
                        @if($portfolio['images']->count() > 1)
                        <button 
                            @click.stop="prev()"
                            class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/90 backdrop-blur flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white hover:scale-110"
                        >
                            <svg class="w-4 h-4 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        
                        <button 
                            @click.stop="next()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/90 backdrop-blur flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white hover:scale-110"
                        >
                            <svg class="w-4 h-4 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <!-- Dots Indicators -->
                        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            @foreach($portfolio['images'] as $imgIndex => $image)
                            <button 
                                @click.stop="currentSlide = {{ $imgIndex }}"
                                class="transition-all"
                                :class="currentSlide === {{ $imgIndex }} ? 'w-6 h-1.5 bg-white' : 'w-1.5 h-1.5 bg-white/50 hover:bg-white/75'"
                                style="border-radius: 9999px;"
                            ></button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <script>
                function carousel{{ $index }}() {
                    return {
                        currentSlide: 0,
                        totalSlides: {{ $portfolio['images']->count() }},
                        autoplayInterval: null,
                        images: @json($portfolio['images']->map(fn($img) => ['title' => $img->title])),
                        
                        init() {
                            this.startAutoplay();
                        },
                        
                        next() {
                            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                        },
                        
                        prev() {
                            this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
                        },
                        
                        startAutoplay() {
                            if (this.totalSlides > 1) {
                                this.autoplayInterval = setInterval(() => {
                                    this.next();
                                }, 4000);
                            }
                        },
                        
                        stopAutoplay() {
                            if (this.autoplayInterval) {
                                clearInterval(this.autoplayInterval);
                            }
                        }
                    }
                }
                </script>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-gray-400 text-lg">Nenhuma imagem no portfólio ainda</p>
            </div>
            @endif
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="orcamento" class="py-24 bg-[hsl(60,20%,98%)]">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <span class="inline-block px-4 py-2 rounded-full bg-[hsl(142,50%,35%)]/10 text-[hsl(142,50%,35%)] text-sm font-medium mb-4">
                        {{ $settings['contact_tag'] ?? 'Orçamento' }}
                    </span>
                    <h2 class="font-display text-3xl md:text-5xl font-bold mb-4">
                        {{ $settings['contact_title'] ?? 'Solicite seu Orçamento' }}
                    </h2>
                    <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                        {{ $settings['contact_description'] ?? 'Preencha o formulário abaixo.' }}
                    </p>
                </div>

                <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12 border border-[hsl(90,20%,85%)]">
                    @if(session('success'))
                    <div class="text-center py-12 animate-fade-up">
                        <div class="w-20 h-20 rounded-full bg-[hsl(142,50%,35%)]/10 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-2xl font-semibold mb-2">Mensagem Enviada!</h3>
                        <p class="text-gray-600">{{ session('success') }}</p>
                    </div>
                    @else
                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2">Nome Completo *</label>
                                <input type="text" id="name" name="name" required placeholder="Seu nome" 
                                    class="w-full px-4 py-3 rounded-xl border border-[hsl(90,20%,85%)] bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium mb-2">Número de Telefone *</label>
                                <input type="tel" id="phone" name="phone" required placeholder="(00) 00000-0000"
                                    class="w-full px-4 py-3 rounded-xl border border-[hsl(90,20%,85%)] bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium mb-2">Descrição do Projeto *</label>
                            <textarea id="message" name="message" required rows="5" placeholder="Descreva brevemente o que você precisa..."
                                class="w-full px-4 py-3 rounded-xl border border-[hsl(90,20%,85%)] bg-white focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] focus:border-transparent transition-all resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg text-lg font-semibold bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] hover:shadow-lg hover:scale-105 transition-all h-14 px-10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Enviar Solicitação
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[hsl(150,40%,25%)] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Logo & Description -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        @if(isset($settings['logo_url']) && $settings['logo_url'])
                            <img src="{{ asset('storage/' . $settings['logo_url']) }}" alt="Logo" class="h-10 w-auto object-contain">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <span class="font-display text-xl font-semibold">{{ $settings['logo_text'] ?? 'RM Jardim' }}</span>
                        @endif
                    </div>
                    <p class="text-white/70 leading-relaxed">
                        {{ $settings['footer_description'] ?? 'Transformando espaços em jardins dos sonhos.' }}
                    </p>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="font-display text-lg font-semibold mb-4">Contato</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-white/70">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $settings['footer_phone'] ?? '(00) 00000-0000' }}</span>
                        </li>
                        <li class="flex items-center gap-3 text-white/70">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $settings['footer_email'] ?? 'contato@email.com' }}</span>
                        </li>
                        <li class="flex items-center gap-3 text-white/70">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $settings['footer_address'] ?? 'Sua cidade, Estado' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div>
                    <h3 class="font-display text-lg font-semibold mb-4">Redes Sociais</h3>
                    <div class="flex gap-4">
                        <a href="{{ $settings['instagram_url'] ?? '#' }}" target="_blank" class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="{{ $settings['facebook_url'] ?? '#' }}" target="_blank" class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 mt-12 pt-8 text-center">
                <p class="text-white/50 text-sm">
                    © {{ date('Y') }} {{ $settings['footer_copyright'] ?? 'RM Jardim' }}. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>