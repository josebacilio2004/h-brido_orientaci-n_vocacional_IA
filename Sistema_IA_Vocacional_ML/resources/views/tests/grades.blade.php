@extends('layouts.dashboard')

@section('title', 'Predicción con IA')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 p-6">
    <div class="max-w-4xl mx-auto">
         Header 
        <div class="mb-8">
            <a href="{{ route('tests.index') }}" class="text-gray-300 hover:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Tests
            </a>
            <h1 class="text-4xl font-bold text-white mb-2">Predicción con Inteligencia Artificial</h1>
            <p class="text-gray-300">Ingresa tus notas académicas para obtener una recomendación personalizada</p>
        </div>

        @if(session('error'))
        <div class="bg-red-500/20 border border-red-500 text-red-100 px-6 py-4 rounded-lg mb-6">
            {{ session('error') }}
        </div>
        @endif

         Formulario de Notas 
        <form action="{{ route('tests.grades.submit') }}" method="POST" class="bg-white/10 backdrop-blur-lg rounded-xl p-8 border border-white/20">
            @csrf
            
            <div class="mb-6">
                <div class="bg-blue-500/20 border border-blue-500 text-blue-100 px-6 py-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold mb-1">¿Cómo funciona?</p>
                            <p class="text-sm">Nuestro modelo de Machine Learning analiza tus calificaciones en todas las materias y predice las carreras universitarias más adecuadas según tu rendimiento académico. Ingresa tus notas del 0 al 20.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                 Matemática 
                <div>
                    <label class="block text-white font-semibold mb-2">Matemática</label>
                    <input type="number" name="nota_matematica" min="0" max="20" 
                           value="{{ old('nota_matematica', $grades->nota_matematica ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_matematica')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 Comunicación 
                <div>
                    <label class="block text-white font-semibold mb-2">Comunicación</label>
                    <input type="number" name="nota_comunicacion" min="0" max="20"
                           value="{{ old('nota_comunicacion', $grades->nota_comunicacion ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_comunicacion')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 Ciencias Sociales 
                <div>
                    <label class="block text-white font-semibold mb-2">Ciencias Sociales</label>
                    <input type="number" name="nota_ciencias_sociales" min="0" max="20"
                           value="{{ old('nota_ciencias_sociales', $grades->nota_ciencias_sociales ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_ciencias_sociales')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 Ciencia y Tecnología 
                <div>
                    <label class="block text-white font-semibold mb-2">Ciencia y Tecnología</label>
                    <input type="number" name="nota_ciencia_tecnologia" min="0" max="20"
                           value="{{ old('nota_ciencia_tecnologia', $grades->nota_ciencia_tecnologia ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_ciencia_tecnologia')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 Desarrollo Personal 
                <div>
                    <label class="block text-white font-semibold mb-2">Desarrollo Personal</label>
                    <input type="number" name="nota_desarrollo_personal" min="0" max="20"
                           value="{{ old('nota_desarrollo_personal', $grades->nota_desarrollo_personal ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_desarrollo_personal')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 Ciudadanía y Cívica 
                <div>
                    <label class="block text-white font-semibold mb-2">Ciudadanía y Cívica</label>
                    <input type="number" name="nota_ciudadania_civica" min="0" max="20"
                           value="{{ old('nota_ciudadania_civica', $grades->nota_ciudadania_civica ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_ciudadania_civica')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 Educación Física 
                <div>
                    <label class="block text-white font-semibold mb-2">Educación Física</label>
                    <input type="number" name="nota_educacion_fisica" min="0" max="20"
                           value="{{ old('nota_educacion_fisica', $grades->nota_educacion_fisica ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_educacion_fisica')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 Inglés 
                <div>
                    <label class="block text-white font-semibold mb-2">Inglés</label>
                    <input type="number" name="nota_ingles" min="0" max="20"
                           value="{{ old('nota_ingles', $grades->nota_ingles ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_ingles')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 Educación para el Trabajo 
                <div class="md:col-span-2">
                    <label class="block text-white font-semibold mb-2">Educación para el Trabajo</label>
                    <input type="number" name="nota_educacion_trabajo" min="0" max="20"
                           value="{{ old('nota_educacion_trabajo', $grades->nota_educacion_trabajo ?? '') }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:border-purple-500"
                           placeholder="0-20" required>
                    @error('nota_educacion_trabajo')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-4 rounded-lg font-semibold hover:from-purple-700 hover:to-pink-700 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Obtener Predicción con IA
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
