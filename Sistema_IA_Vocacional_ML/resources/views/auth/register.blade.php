@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo y título -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-white rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">Crear Cuenta</h2>
            <p class="text-indigo-200">Únete al sistema de orientación vocacional</p>
        </div>

        <!-- Formulario de registro -->
        <div class="glass-effect rounded-xl p-8">
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-white mb-2">
                        Nombre completo
                    </label>
                    <input id="name" name="name" type="text" required 
                           class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"
                           placeholder="Tu nombre completo" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

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
                    <label for="grade" class="block text-sm font-medium text-white mb-2">
                        Grado
                    </label>
                    <select id="grade" name="grade" required 
                            class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent">
                        <option value="" class="text-gray-900">Selecciona tu grado</option>
                        <option value="5to_secundaria" class="text-gray-900" {{ old('grade') == '5to_secundaria' ? 'selected' : '' }}>5to de Secundaria</option>
                        <option value="egresado" class="text-gray-900" {{ old('grade') == 'egresado' ? 'selected' : '' }}>Egresado</option>
                    </select>
                    @error('grade')
                        <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="school" class="block text-sm font-medium text-white mb-2">
                        Institución educativa
                    </label>
                    <input id="school" name="school" type="text" required 
                           class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"
                           placeholder="Nombre de tu colegio" value="{{ old('school') }}">
                    @error('school')
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

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                        Confirmar contraseña
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"
                           placeholder="••••••••">
                </div>

                <div>
                    <button type="submit" class="w-full btn-primary">
                        Crear Cuenta
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-white/80">
                        ¿Ya tienes cuenta? 
                        <a href="{{ route('login') }}" class="text-indigo-200 hover:text-white font-medium transition-colors">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
