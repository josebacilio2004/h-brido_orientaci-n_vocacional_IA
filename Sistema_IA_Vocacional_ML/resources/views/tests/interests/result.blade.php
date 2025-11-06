@extends('layouts.dashboard')

@section('title', 'Resultados - Test de Intereses')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-8">
            <div class="mx-auto h-24 w-24 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-full flex items-center justify-center mb-4 animate-bounce">
                <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">¡Test Completado!</h1>
            <p class="text-gray-300 text-lg">Aquí están tus resultados de intereses</p>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-4">Tu Perfil de Intereses</h2>
            <div class="text-gray-300 leading-relaxed">
                <p>{{ $analysis }}</p>
            </div>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-6">
            <h2 class="text-2xl font-bold text-white mb-6">Tus Áreas de Interés</h2>
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
                                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('tests.index') }}" class="flex-1 bg-white/10 text-white px-6 py-4 rounded-lg font-semibold hover:bg-white/20 transition-all text-center">
                Volver a Tests
            </a>
            <a href="{{ route('dashboard') }}"class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-6 py-4 rounded-lg font-semibold hover:from-blue-700 hover:to-cyan-700 transition-all text-center">
                Ir al Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
