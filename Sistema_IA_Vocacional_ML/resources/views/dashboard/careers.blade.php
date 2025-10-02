@extends('layouts.dashboard')

@section('title', 'Explorar Carreras')
@section('page-title', 'Explorar Carreras')
@section('page-description', 'Descubre más de 200 carreras universitarias')

@section('content')
<div class="space-y-6">
    <!-- Filtros y Búsqueda -->
    <div class="glass-card rounded-xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" placeholder="Buscar carreras..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex space-x-4">
                <select class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option>Todas las áreas</option>
                    <option>Ciencias de la Salud</option>
                    <option>Ingeniería y Tecnología</option>
                    <option>Ciencias Sociales</option>
                    <option>Arte y Humanidades</option>
                    <option>Ciencias Exactas</option>
                    <option>Economía y Negocios</option>
                </select>
                <button class="btn-primary">Filtrar</button>
            </div>
        </div>
    </div>

    <!-- Carreras Recomendadas -->
    <div class="glass-card rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Carreras Recomendadas para Ti</h3>
            <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full">Basado en IA</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Carrera 1 -->
            <div class="module-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">95% match</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Ingeniería de Software</h4>
                <p class="text-gray-600 text-sm mb-4">Diseña y desarrolla sistemas de software innovadores para resolver problemas complejos.</p>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Salario: S/. 4,500 - 8,000
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Duración: 5 años
                    </div>
                </div>
                <button class="btn-secondary w-full text-sm">Ver Detalles</button>
            </div>

            <!-- Carrera 2 -->
            <div class="module-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">88% match</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Psicología</h4>
                <p class="text-gray-600 text-sm mb-4">Estudia el comportamiento humano y ayuda a las personas a superar sus desafíos.</p>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Salario: S/. 2,800 - 5,500
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Duración: 5 años
                    </div>
                </div>
                <button class="btn-secondary w-full text-sm">Ver Detalles</button>
            </div>

            <!-- Carrera 3 -->
            <div class="module-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">82% match</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Administración</h4>
                <p class="text-gray-600 text-sm mb-4">Lidera organizaciones y toma decisiones estratégicas para el éxito empresarial.</p>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Salario: S/. 3,200 - 7,000
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Duración: 5 años
                    </div>
                </div>
                <button class="btn-secondary w-full text-sm">Ver Detalles</button>
            </div>
        </div>
    </div>

    <!-- Todas las Carreras -->
    <div class="glass-card rounded-xl p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Todas las Carreras</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Lista de carreras -->
            <div class="space-y-3">
                <h4 class="font-semibold text-gray-900 mb-3">Ciencias de la Salud</h4>
                <div class="space-y-2">
                    <a href="#" class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                        <span class="text-gray-900">Medicina</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                        <span class="text-gray-900">Enfermería</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                        <span class="text-gray-900">Odontología</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="space-y-3">
                <h4 class="font-semibold text-gray-900 mb-3">Ingeniería y Tecnología</h4>
                <div class="space-y-2">
                    <a href="#" class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                        <span class="text-gray-900">Ingeniería de Sistemas</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                        <span class="text-gray-900">Ingeniería Civil</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                        <span class="text-gray-900">Ingeniería Industrial</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-6">
            <button class="btn-primary">Ver Todas las Carreras (200+)</button>
        </div>
    </div>
</div>
@endsection
