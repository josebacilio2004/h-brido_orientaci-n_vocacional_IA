@extends('layouts.dashboard')

@section('title', 'Resultados del Test')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-5xl mx-auto">
        {{-- Header de éxito --}}
        <div class="text-center mb-8">
            <div class="mx-auto h-24 w-24 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">¡Test Completado!</h1>
            <p class="text-gray-300 text-lg">Aquí están tus resultados personalizados</p>
        </div>

        {{-- Análisis general --}}
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-4">Tu Perfil Vocacional</h2>
            <div class="text-gray-300 leading-relaxed">
                {!! $result->analysis !!}
            </div>
        </div>

        {{-- Puntajes por categoría --}}
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-6">Tus Áreas de Interés</h2>
            <div class="space-y-4">
                @foreach($result->scores as $category => $score)
                @php
                    $percentage = ($score / 50) * 100;
                    $colors = [
                        'realista' => ['from-blue-500', 'to-cyan-500', 'bg-blue-500/20'],
                        'investigador' => ['from-purple-500', 'to-pink-500', 'bg-purple-500/20'],
                        'artistico' => ['from-pink-500', 'to-rose-500', 'bg-pink-500/20'],
                        'social' => ['from-green-500', 'to-emerald-500', 'bg-green-500/20'],
                        'emprendedor' => ['from-orange-500', 'to-red-500', 'bg-orange-500/20'],
                        'convencional' => ['from-indigo-500', 'to-blue-500', 'bg-indigo-500/20']
                    ];
                    $color = $colors[$category] ?? ['from-gray-500', 'to-gray-600', 'bg-gray-500/20'];
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white font-semibold capitalize">{{ ucfirst($category) }}</span>
                        <span class="text-gray-300 font-bold">{{ number_format($percentage, 1) }}%</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r {{ $color[0] }} {{ $color[1] }} h-full rounded-full transition-all duration-1000" 
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Carreras recomendadas --}}
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-6">Carreras Recomendadas</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($result->recommended_careers as $index => $recommendation)
                <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-gradient-to-br from-purple-500 to-pink-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <h3 class="text-white font-bold capitalize">{{ $recommendation['category_name'] }}</h3>
                            <p class="text-gray-400 text-sm">{{ $recommendation['percentage'] }}% de afinidad</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">{{ $recommendation['description'] }}</p>
                    <div class="space-y-2">
                        <p class="text-white font-semibold text-sm mb-2">Carreras sugeridas:</p>
                        @foreach(array_slice($recommendation['careers'], 0, 5) as $career)
                        <div class="flex items-center gap-2 text-gray-300 text-sm">
                            <svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>{{ $career }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex gap-4">
            <a href="{{ route('tests.index') }}" 
               class="flex-1 bg-white/10 text-white px-6 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all text-center">
                Volver a Tests
            </a>
            <a href="{{ route('dashboard.careers') }}" 
               class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-4 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all text-center">
                Explorar Carreras
            </a>
            <button onclick="window.print()" 
                    class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-cyan-700 transition-all text-center">
                Descargar Resultados
            </button>
        </div>
    </div>
</div>
@endsection
