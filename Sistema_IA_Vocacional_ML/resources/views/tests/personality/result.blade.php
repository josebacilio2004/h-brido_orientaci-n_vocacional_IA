@extends('layouts.dashboard')

@section('title', 'Resultados - Test de Personalidad')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-8">
            <div class="mx-auto h-24 w-24 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">¡Test Completado!</h1>
            <p class="text-gray-300 text-lg">Aquí están tus resultados de personalidad</p>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-4">Tu Tipo de Personalidad</h2>
            <div class="text-gray-300 leading-relaxed">
                <p>{{ $analysis }}</p>
            </div>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-6">Rasgos de Personalidad</h2>
            <div class="space-y-4">
                @if(is_array($scores))
                    @foreach ($scores as $category => $score)
                        @php $percentage = ($score / 50) * 100; @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-semibold capitalize">{{ ucfirst(str_replace('_', ' ', $category)) }}</span>
                                <span class="text-gray-300 font-bold">{{ number_format($percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-4 overflow-hidden">
                                <div class="bg-gradient-to-r from-pink-500 to-rose-500 h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="flex gap-4">
            <!-- Cambiar ruta de tests.personality.index a tests.index porque esa ruta no existe -->
            <a href="{{ route('tests.index') }}" class="flex-1 bg-white/10 text-white px-6 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all text-center">
                Volver a Tests
            </a>
            <a href="{{ route('dashboard') }}" class="flex-1 bg-gradient-to-r from-pink-600 to-rose-600 text-white px-6 py-4 rounded-lg font-semibold hover:from-pink-700 hover:to-rose-700 transition-all text-center">
                Ir al Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
