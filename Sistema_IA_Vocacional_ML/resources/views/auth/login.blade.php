@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo y título -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-white rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">Sistema IA Vocacional</h2>
            <p class="text-indigo-200">Descubre tu futuro profesional</p>
        </div>

        <!-- Formulario de login -->
        <div class="glass-effect rounded-xl p-8">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        Correo electrónico
                    </label>
                    <input id="email" name="email" type="email" required 
                           class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"
                           placeholder="tu@email.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        Contraseña
                    </label>
                    <input id="password" name="password" type="password" required 
                           class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-white">
                            Recordarme
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="text-indigo-200 hover:text-white transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full btn-primary">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-white/80">
                        ¿No tienes cuenta? 
                        <a href="{{ route('register') }}" class="text-indigo-200 hover:text-white font-medium transition-colors">
                            Regístrate aquí
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
