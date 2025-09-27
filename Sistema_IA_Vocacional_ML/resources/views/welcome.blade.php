@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <div class="mx-auto h-24 w-24 bg-white rounded-full flex items-center justify-center mb-8 shadow-lg">
                    <svg class="h-12 w-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                    FuturoSmart
                </h1>
                <p class="text-xl md:text-2xl text-indigo-200 mb-8 max-w-3xl mx-auto">
                    Descubre tu futuro profesional con inteligencia artificial. 
                    Diseñado especialmente para estudiantes de 5to de secundaria.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @guest
                        <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-4">
                            Comenzar Ahora
                        </a>
                        <a href="{{ route('login') }}" class="btn-secondary text-lg px-8 py-4">
                            Iniciar Sesión
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-primary text-lg px-8 py-4">
                            Ir al Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-24 bg-white/10 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">¿Por qué elegir nuestro sistema?</h2>
                <p class="text-xl text-indigo-200 max-w-2xl mx-auto">
                    Utilizamos la última tecnología en inteligencia artificial para brindarte 
                    recomendaciones precisas y personalizadas.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-effect rounded-xl p-8 text-center">
                    <div class="h-16 w-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Inteligencia Artificial</h3>
                    <p class="text-indigo-200">
                        Nuestro sistema analiza tus respuestas con algoritmos avanzados para 
                        brindarte recomendaciones precisas y personalizadas.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-effect rounded-xl p-8 text-center">
                    <div class="h-16 w-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Tests Especializados</h3>
                    <p class="text-indigo-200">
                        Evaluaciones diseñadas específicamente para estudiantes peruanos, 
                        considerando el contexto educativo y laboral local.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-effect rounded-xl p-8 text-center">
                    <div class="h-16 w-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">200+ Carreras</h3>
                    <p class="text-indigo-200">
                        Explora más de 200 carreras universitarias con información detallada 
                        sobre cada una, incluyendo universidades y perspectivas laborales.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-24">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-white mb-6">
                ¿Listo para descubrir tu vocación?
            </h2>
            <p class="text-xl text-indigo-200 mb-8">
                Únete a miles de estudiantes que ya han encontrado su camino profesional 
                con nuestro sistema de orientación vocacional.
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-4">
                    Crear Cuenta Gratis
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn-primary text-lg px-8 py-4">
                    Continuar mi Evaluación
                </a>
            @endguest
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black/20 backdrop-blur-sm py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="h-8 w-8 bg-white rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-xl">Sistema IA Vocacional</span>
                </div>
                <p class="text-indigo-200 mb-6">
                    Ayudando a estudiantes a descubrir su futuro profesional desde 2024
                </p>
                <div class="flex justify-center space-x-6 text-indigo-200">
                    <a href="#" class="hover:text-white transition-colors">Términos de Uso</a>
                    <a href="#" class="hover:text-white transition-colors">Política de Privacidad</a>
                    <a href="#" class="hover:text-white transition-colors">Contacto</a>
                </div>
                <p class="text-indigo-300 text-sm mt-6">
                    © 2025 Sistema IA Vocacional. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>
</div>
@endsection
