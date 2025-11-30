<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['site_name'] ?? 'RM Jardim' }} - {{ $settings['site_description'] ?? 'Serviços de Jardinagem' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    
    {{-- Header Fixo --}}
    <header class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            {{-- Logo (placeholder) --}}
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-700 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-xl">RM</span>
                </div>
                <span class="ml-3 text-xl font-bold text-green-800">{{ $settings['site_name'] ?? 'RM Jardim' }}</span>
            </div>
            
            {{-- Botão WhatsApp --}}
            <a href="https://wa.me/{{ $settings['phone'] ?? '' }}" target="_blank" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full font-semibold transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
            </a>
        </div>
    </header>

    {{-- Hero Section com Formulário --}}
    <section class="pt-24 pb-16 bg-gradient-to-b from-green-50 to-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                {{-- Texto --}}
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-green-900 mb-4">
                        Transforme seu Jardim em um Paraíso Verde
                    </h1>
                    <p class="text-lg text-gray-700 mb-6">
                        {{ $settings['site_description'] ?? 'Serviços profissionais de jardinagem com qualidade e dedicação' }}
                    </p>
                </div>

                {{-- Formulário de Contato --}}
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-green-800 mb-6">Solicite um Orçamento</h2>
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefone/WhatsApp</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="(19) 99999-9999">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensagem</label>
                            <textarea id="message" name="message" rows="4" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Descreva o serviço que precisa...">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Galeria --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-green-900 text-center mb-12">Nossos Trabalhos</h2>
            
            @if($images->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($images as $image)
                        <div class="rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="{{ $image->title ?? 'Trabalho RM Jardim' }}"
                                 class="w-full h-64 object-cover">
                            @if($image->title)
                                <div class="p-4 bg-white">
                                    <h3 class="font-semibold text-gray-800">{{ $image->title }}</h3>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Placeholders enquanto não tem imagens --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @for($i = 1; $i <= 6; $i++)
                        <div class="rounded-lg overflow-hidden shadow-lg bg-gray-200 h-64 flex items-center justify-center">
                            <span class="text-gray-400 text-lg">Imagem {{ $i }}</span>
                        </div>
                    @endfor
                </div>
            @endif
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-green-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h3 class="font-bold text-xl mb-4">{{ $settings['site_name'] ?? 'RM Jardim' }}</h3>
                    <p class="text-green-200">{{ $settings['site_description'] ?? 'Serviços profissionais de jardinagem' }}</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-xl mb-4">Contato</h3>
                    <p class="text-green-200">📱 {{ $settings['phone'] ?? '' }}</p>
                    <p class="text-green-200">✉️ {{ $settings['email'] ?? '' }}</p>
                </div>
                
                <div>
                    <h3 class="font-bold text-xl mb-4">Redes Sociais</h3>
                    <div class="flex gap-4 justify-center md:justify-start">
                        <a href="https://wa.me/{{ $settings['phone'] ?? '' }}" target="_blank" 
                           class="bg-green-700 hover:bg-green-600 p-3 rounded-full transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-green-800 mt-8 pt-8 text-center text-green-200">
                <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'RM Jardim' }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

</body>
</html>