@extends('layouts.dashboard')

@section('title', 'Test de Habilidades')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Test de Habilidades</h1>
            <p class="text-gray-300">Evalúa tus fortalezas y competencias naturales</p>
        </div>

        @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-100 px-6 py-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid md:grid-cols-1 lg:grid-cols-1 gap-6">
            @forelse($tests as $test)
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 hover:border-orange-400 transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-gradient-to-br from-orange-500 to-red-500 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    @if(in_array($test->id, $completedTests))
                    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full font-semibold">Completado</span>
                    @endif
                </div>

                <h3 class="text-xl font-bold text-white mb-2">{{ $test->title }}</h3>
                <p class="text-gray-300 text-sm mb-4">{{ $test->description }}</p>

                <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $test->duration_minutes }} min
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        {{ $test->total_questions }} preguntas
                    </span>
                </div>

                @if(in_array($test->id, $completedTests))
                <a href="{{ route('tests.skills.result', $test->id) }}" class="block w-full text-center bg-white/20 text-white px-4 py-2 rounded-lg font-semibold hover:bg-white/30 transition-all">
                    Ver Resultados
                </a>
                @else
                <a href="{{ route('tests.skills.show', $test->id) }}" class="block w-full text-center bg-gradient-to-r from-orange-600 to-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-orange-700 hover:to-red-700 transition-all">
                    Comenzar Test
                </a>
                @endif
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-400 text-lg">No hay tests de habilidades disponibles.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
