@extends('layouts.dashboard')

@section('title', 'Tests Vocacionales')
@section('page-title', 'Tests Vocacionales')
@section('page-description', 'Descubre tu vocación con nuestros tests inteligentes')

@section('content')
<div class="space-y-6">
    <!-- Test Principal -->
    <div class="glass-card rounded-xl p-8">
        <div class="text-center mb-8">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Test Vocacional con IA</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Nuestro test utiliza inteligencia artificial para analizar tus respuestas y brindarte recomendaciones precisas sobre tu futuro profesional.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="text-center">
                <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">15-20 minutos</h3>
                <p class="text-sm text-gray-600">Tiempo estimado</p>
            </div>
            <div class="text-center">
                <div class="h-12 w-12 bg-cyan-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="h-6 w-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">60 preguntas</h3>
                <p class="text-sm text-gray-600">Análisis completo</p>
            </div>
            <div class="text-center">
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Resultados IA</h3>
                <p class="text-sm text-gray-600">Análisis inteligente</p>
            </div>
        </div>

        <div class="text-center">
            <button class="btn-primary text-lg px-8 py-4" onclick="startTest()">
                Comenzar Test Vocacional
            </button>
            <p class="text-sm text-gray-500 mt-2">Puedes pausar y continuar cuando quieras</p>
        </div>
    </div>

    <!-- Tests Disponibles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Test de Intereses -->
        <div class="module-card">
            <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mb-4">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Test de Intereses</h3>
            <p class="text-gray-600 mb-4">Identifica qué actividades y áreas del conocimiento te motivan más.</p>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">10 min</span>
                <button class="btn-secondary text-sm px-4 py-2">Realizar</button>
            </div>
        </div>

        <!-- Test de Habilidades -->
        <div class="module-card">
            <div class="h-12 w-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mb-4">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Test de Habilidades</h3>
            <p class="text-gray-600 mb-4">Evalúa tus fortalezas y competencias naturales.</p>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">15 min</span>
                <button class="btn-secondary text-sm px-4 py-2">Realizar</button>
            </div>
        </div>

        <!-- Test de Personalidad -->
        <div class="module-card">
            <div class="h-12 w-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mb-4">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Test de Personalidad</h3>
            <p class="text-gray-600 mb-4">Conoce tu tipo de personalidad y cómo influye en tu carrera.</p>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">12 min</span>
                <button class="btn-secondary text-sm px-4 py-2">Realizar</button>
            </div>
        </div>
    </div>

    <!-- Historial de Tests -->
    <div class="glass-card rounded-xl p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Historial de Tests</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Test Vocacional Completo</p>
                        <p class="text-sm text-gray-500">Completado el 15 de Enero, 2024</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Completado</span>
                    <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Ver Resultados</button>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="h-10 w-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Test de Intereses</p>
                        <p class="text-sm text-gray-500">Iniciado el 20 de Enero, 2024</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">En progreso</span>
                    <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function startTest() {
    // Aquí iría la lógica para iniciar el test
    alert('¡Próximamente! El test vocacional estará disponible en la siguiente actualización.');
}
</script>
@endsection
