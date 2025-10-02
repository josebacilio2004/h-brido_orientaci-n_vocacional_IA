@extends('layouts.dashboard')

@section('title', 'Recomendaciones IA')
@section('page-title', 'Recomendaciones Personalizadas')
@section('page-description', 'Sugerencias inteligentes basadas en tu perfil')

@section('content')
<div class="space-y-6">
    <!-- Header con IA -->
    <div class="glass-card rounded-xl p-8 text-center">
        <div class="mx-auto h-20 w-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mb-4">
            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Recomendaciones con IA</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Nuestro sistema de inteligencia artificial ha analizado tu perfil y te ofrece recomendaciones personalizadas para tu futuro profesional.</p>
    </div>

    <!-- Recomendaciones Principales -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Carreras Recomendadas -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Carreras Ideales para Ti</h3>
                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">IA</span>
            </div>
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                    <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-4">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Ingeniería de Software</h4>
                        <p class="text-sm text-gray-600">95% de compatibilidad</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                    <div class="h-12 w-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-4">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Ciencia de Datos</h4>
                        <p class="text-sm text-gray-600">88% de compatibilidad</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 88%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                    <div class="h-12 w-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-4">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Psicología</h4>
                        <p class="text-sm text-gray-600">82% de compatibilidad</p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 82%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Universidades Recomendadas -->
        <div class="glass-card rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Universidades Sugeridas</h3>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">Top</span>
            </div>
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                    <div class="h-12 w-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-red-600 font-bold text-lg">U</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Universidad Nacional Mayor de San Marcos</h4>
                        <p class="text-sm text-gray-600">Pública • Lima</p>
                        <div class="flex items-center mt-1">
                            <span class="text-yellow-500">★★★★★</span>
                            <span class="text-sm text-gray-500 ml-2">4.8/5</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-blue-600 font-bold text-lg">P</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Pontificia Universidad Católica del Perú</h4>
                        <p class="text-sm text-gray-600">Privada • Lima</p>
                        <div class="flex items-center mt-1">
                            <span class="text-yellow-500">★★★★★</span>
                            <span class="text-sm text-gray-500 ml-2">4.7/5</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-green-600 font-bold text-lg">U</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Universidad de Lima</h4>
                        <p class="text-sm text-gray-600">Privada • Lima</p>
                        <div class="flex items-center mt-1">
                            <span class="text-yellow-500">★★★★☆</span>
                            <span class="text-sm text-gray-500 ml-2">4.5/5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consejos Personalizados -->
    <div class="glass-card rounded-xl p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Consejos Personalizados</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                <div class="h-10 w-10 bg-blue-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Desarrolla tus habilidades técnicas</h4>
                <p class="text-gray-600 text-sm">Basado en tu perfil, te recomendamos fortalecer tus conocimientos en programación y matemáticas.</p>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200">
                <div class="h-10 w-10 bg-green-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Participa en proyectos grupales</h4>
                <p class="text-gray-600 text-sm">Tu perfil muestra buenas habilidades de liderazgo. Busca oportunidades para liderar equipos.</p>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-6 border border-purple-200">
                <div class="h-10 w-10 bg-purple-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 01-2 2H10a2 2 0 01-2-2V6m8 0H8"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Explora prácticas profesionales</h4>
                <p class="text-gray-600 text-sm">Considera realizar prácticas en empresas tecnológicas para ganar experiencia real.</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg p-6 border border-yellow-200">
                <div class="h-10 w-10 bg-yellow-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">Mantente actualizado</h4>
                <p class="text-gray-600 text-sm">El campo tecnológico evoluciona rápidamente. Sigue blogs y cursos online para estar al día.</p>
            </div>
        </div>
    </div>

    <!-- Próximos Pasos -->
    <div class="glass-card rounded-xl p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Próximos Pasos Recomendados</h3>
        <div class="space-y-4">
            <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                <div class="h-8 w-8 bg-indigo-500 rounded-full flex items-center justify-center mr-4">
                    <span class="text-white font-bold text-sm">1</span>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">Completa tu test vocacional</h4>
                    <p class="text-sm text-gray-600">Obtén un análisis más detallado de tu perfil</p>
                </div>
                <a href="{{ route('dashboard.tests') }}" class="btn-primary text-sm px-4 py-2">Realizar</a>
            </div>

            <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                <div class="h-8 w-8 bg-cyan-500 rounded-full flex items-center justify-center mr-4">
                    <span class="text-white font-bold text-sm">2</span>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">Investiga las carreras recomendadas</h4>
                    <p class="text-sm text-gray-600">Lee más sobre las opciones que mejor se adaptan a ti</p>
                </div>
                <a href="{{ route('dashboard.careers') }}" class="btn-secondary text-sm px-4 py-2">Explorar</a>
            </div>

            <div class="flex items-center p-4 bg-white rounded-lg border border-gray-200">
                <div class="h-8 w-8 bg-green-500 rounded-full flex items-center justify-center mr-4">
                    <span class="text-white font-bold text-sm">3</span>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">Contacta con universidades</h4>
                    <p class="text-sm text-gray-600">Solicita información sobre admisión y becas</p>
                </div>
                <button class="btn-primary text-sm px-4 py-2" style="background: var(--gradient-success);">Contactar</button>
            </div>
        </div>
    </div>
</div>
@endsection
