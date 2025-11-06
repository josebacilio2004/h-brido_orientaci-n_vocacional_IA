@extends('layouts.dashboard')

@section('title', 'Resultados - Test de Habilidades')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-8">
            <div class="mx-auto h-24 w-24 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">¡Test Completado!</h1>
            <p class="text-gray-300 text-lg">Aquí están tus resultados de habilidades</p>
        </div>

        <!-- Debug temporal - mostrar datos recibidos -->
        @if(!isset($scores) || empty($scores))
        <div class="bg-red-500/20 border border-red-500 rounded-lg p-4 mb-6">
            <p class="text-red-100">⚠️ No se recibieron datos de puntuaciones. Esto puede ser un error.</p>
            <p class="text-red-100 text-sm">Datos recibidos: scores={{ isset($scores) ? 'SÍ' : 'NO' }}, analysis={{ isset($analysis) ? 'SÍ' : 'NO' }}</p>
        </div>
        @endif

        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-4">Tu Perfil de Habilidades</h2>
            <div class="text-gray-300 leading-relaxed">
                <p>{{ $analysis ?? 'No hay análisis disponible' }}</p>
            </div>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-6">Tus Habilidades Principales</h2>
            <div class="space-y-4">
                @if(is_array($scores) && count($scores) > 0)
                    @foreach ($scores as $category => $score)
                        @php $percentage = ($score / 50) * 100; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-semibold capitalize">{{ ucfirst(str_replace('_', ' ', $category)) }}</span>
                                <span class="text-gray-300 font-bold">{{ number_format($percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-4 overflow-hidden">
                                <div class="bg-gradient-to-r from-orange-500 to-red-500 h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p>No hay datos de habilidades disponibles.</p>
                        <p class="text-sm mt-2">Esto puede deberse a un error al procesar el test.</p>
                    </div>
                @endif
            </div>
        </div>

        @if(isset($recommendedCareers) && is_array($recommendedCareers) && count($recommendedCareers) > 0)
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-6">Carreras Recomendadas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($recommendedCareers as $career)
                    <div class="bg-white/5 rounded-lg p-4 border border-white/10 hover:bg-white/10 transition-all">
                        <h3 class="text-white font-semibold mb-2">{{ $career }}</h3>
                        <p class="text-gray-400 text-sm">Basado en tus habilidades principales</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex gap-4">
            <!-- ✅ CORREGIDO: Cambiar tests.skills.index por tests.index -->
            <a href="{{ route('tests.index') }}" class="flex-1 bg-white/10 text-white px-6 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all text-center">
                Volver a Tests
            </a>
            <a href="{{ route('dashboard') }}" class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 text-white px-6 py-4 rounded-lg font-semibold hover:from-orange-700 hover:to-red-700 transition-all text-center">
                Ir al Dashboard
            </a>
        </div>
    </div>
</div>
@endsection