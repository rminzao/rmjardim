<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | RM Jardim</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-display { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[hsl(60,30%,96%)] font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-lg border border-[hsl(90,20%,85%)] p-8">
                <!-- Icon -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 rounded-full bg-[hsl(142,50%,35%)]/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-[hsl(142,50%,35%)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h1 class="font-display text-2xl font-bold text-[hsl(150,30%,15%)]">
                        Área Administrativa
                    </h1>
                    <p class="text-gray-500 mt-2">
                        Digite suas credenciais para acessar
                    </p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            placeholder="seu@email.com"
                            class="w-full px-4 py-3 rounded-xl border border-[hsl(90,20%,85%)] bg-[hsl(60,30%,96%)] text-[hsl(150,30%,15%)] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] focus:border-transparent transition-all"
                        >
                        @error('email')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[hsl(150,30%,15%)] mb-2">
                            Senha
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'"
                                id="password" 
                                name="password" 
                                required
                                placeholder="••••••••"
                                class="w-full px-4 py-3 pr-12 rounded-xl border border-[hsl(90,20%,85%)] bg-[hsl(60,30%,96%)] text-[hsl(150,30%,15%)] placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[hsl(142,50%,35%)] focus:border-transparent transition-all"
                            >
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[hsl(150,30%,15%)] transition-colors"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember"
                            class="w-4 h-4 rounded border-[hsl(90,20%,85%)] text-[hsl(142,50%,35%)] focus:ring-[hsl(142,50%,35%)]"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-600">
                            Manter conectado
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg text-lg font-semibold bg-[hsl(142,50%,35%)] text-white hover:bg-[hsl(150,40%,25%)] hover:shadow-lg hover:scale-105 transition-all h-14 px-10"
                    >
                        Entrar
                    </button>
                </form>

                @if (Route::has('password.request'))
                    <div class="text-center mt-6">
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-[hsl(142,50%,35%)] transition-colors">
                            Esqueceu sua senha?
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>