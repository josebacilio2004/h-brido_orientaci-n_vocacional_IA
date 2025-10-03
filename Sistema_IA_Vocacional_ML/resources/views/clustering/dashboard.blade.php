@extends('layouts.dashboard')

@section('title', 'Análisis de Clustering - Dashboard')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
        <div class="max-w-7xl mx-auto">
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">Análisis de Clustering Vocacional</h1>
                <p class="text-gray-400">Análisis de perfiles vocacionales usando K-Means Clustering</p>
            </div>

            @if (isset($error))
                <div class="bg-red-500/20 border border-red-500 text-red-200 px-6 py-4 rounded-lg mb-6">
                    {{ $error }}
                </div>
            @else
                {{-- Estadísticas generales --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Total de Tests</p>
                                <p class="text-3xl font-bold text-white">{{ $trends['total_tests'] }}</p>
                            </div>
                            <div class="bg-purple-600/30 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Clusters Identificados</p>
                                <p class="text-3xl font-bold text-white">{{ count($clusterStats) }}</p>
                            </div>
                            <div class="bg-blue-600/30 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Perfil Más Popular</p>
                                <p class="text-xl font-bold text-white capitalize">{{ $trends['most_popular_profile'] }}</p>
                            </div>
                            <div class="bg-green-600/30 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Tasa de Completitud</p>
                                <p class="text-3xl font-bold text-white">{{ $trends['completion_rate'] }}%</p>
                            </div>
                            <div class="bg-pink-600/30 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-pink-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Clusters identificados --}}
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Clusters Identificados</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($clusterStats as $cluster)
                            <div class="bg-white/5 rounded-lg p-6 border border-white/10 hover:border-purple-500/50 transition-all">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-bold text-white capitalize">{{ $cluster['dominant_profile'] }}</h3>
                                    <span
                                        class="bg-purple-600/30 text-purple-200 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $cluster['percentage'] }}%
                                    </span>
                                </div>

                                <p class="text-gray-400 text-sm mb-4">
                                    <span class="font-semibold text-white">{{ $cluster['size'] }}</span> estudiantes en este
                                    cluster
                                </p>

                                {{-- Puntajes promedio --}}
                                <div class="mb-4">
                                    <p class="text-gray-400 text-xs mb-2">Puntajes Promedio:</p>
                                    <div class="space-y-2">
                                        @foreach ($cluster['avg_scores'] as $category => $score)
                                            <div>
                                                <div class="flex justify-between text-xs mb-1">
                                                    <span class="text-gray-400 capitalize">{{ $category }}</span>
                                                    <span class="text-white font-semibold">{{ $score }}</span>
                                                </div>
                                                <div class="w-full bg-white/10 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 h-2 rounded-full"
                                                        style="width: {{ ($score / 50) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Carreras principales --}}
                                <div>
                                    <p class="text-gray-400 text-xs mb-2">Carreras Principales:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach (array_slice($cluster['top_careers'], 0, 3) as $career)
                                            <span
                                                class="bg-blue-600/20 text-blue-200 px-2 py-1 rounded text-xs">{{ $career }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Distribución de puntajes promedio --}}
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20 mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Distribución de Puntajes Promedio</h2>

                    <div class="space-y-4">
                        @foreach ($trends['avg_scores'] as $category => $score)
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-300 font-medium capitalize">{{ $category }}</span>
                                    <span class="text-white font-bold">{{ $score }} / 50</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-4">
                                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 h-4 rounded-full transition-all"
                                        style="width: {{ ($score / 50) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Top 10 carreras más recomendadas --}}
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20">
                    <h2 class="text-2xl font-bold text-white mb-6">Top 10 Carreras Más Recomendadas</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($trends['top_careers'] as $career => $count)
                            <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-300 font-medium">{{ $career }}</span>
                                    <span class="bg-purple-600/30 text-purple-200 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $count }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
