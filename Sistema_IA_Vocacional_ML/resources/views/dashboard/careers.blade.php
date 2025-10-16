@extends('layouts.dashboard')

@section('title', 'Explorar Carreras')
@section('page-title', 'Carreras Universidad Continental')
@section('page-description', 'Descubre las 33 carreras de la Universidad Continental - Huancayo')

@section('content')
<div class="space-y-6">
    <!-- Filtros y Búsqueda -->
    <div class="glass-card rounded-xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Buscar carreras..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex space-x-4">
                <select id="facultyFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Todas las Facultades</option>
                    <option value="Facultad de Ciencias de la Empresa">Ciencias de la Empresa</option>
                    <option value="Facultad de Ingeniería">Ingeniería</option>
                    <option value="Facultad de Humanidades">Humanidades</option>
                    <option value="Facultad de Derecho">Derecho</option>
                    <option value="Facultad de Salud">Salud</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Carreras por Facultad de la Universidad Continental -->
    @php
        $faculties = [
            'Facultad de Ciencias de la Empresa' => 'from-blue-500 to-indigo-600',
            'Facultad de Ingeniería' => 'from-orange-500 to-red-600',
            'Facultad de Humanidades' => 'from-purple-500 to-pink-600',
            'Facultad de Derecho' => 'from-gray-700 to-gray-900',
            'Facultad de Salud' => 'from-green-500 to-emerald-600',
        ];
    @endphp

    @foreach($faculties as $faculty => $gradient)
    <div class="glass-card rounded-xl p-6 faculty-section" data-faculty="{{ $faculty }}">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">{{ $faculty }}</h3>
            <span class="bg-gradient-to-r {{ $gradient }} text-white text-sm font-medium px-4 py-1.5 rounded-full">
                Universidad Continental - Huancayo
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 careers-grid">
            <!-- Las carreras se cargarán dinámicamente aquí -->
        </div>
    </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos de carreras (en producción, esto vendría de la base de datos)
    const careers = @json(\App\Models\Career::all());
    
    function renderCareers(filteredCareers = careers) {
        // Limpiar todas las grillas
        document.querySelectorAll('.careers-grid').forEach(grid => {
            grid.innerHTML = '';
        });
        
        // Agrupar carreras por facultad
        const careersByFaculty = {};
        filteredCareers.forEach(career => {
            if (!careersByFaculty[career.faculty]) {
                careersByFaculty[career.faculty] = [];
            }
            careersByFaculty[career.faculty].push(career);
        });
        
        // Renderizar carreras en cada facultad
        Object.keys(careersByFaculty).forEach(faculty => {
            const section = document.querySelector(`.faculty-section[data-faculty="${faculty}"]`);
            if (section) {
                const grid = section.querySelector('.careers-grid');
                careersByFaculty[faculty].forEach(career => {
                    const careerCard = createCareerCard(career);
                    grid.innerHTML += careerCard;
                });
                section.style.display = 'block';
            }
        });
        
        // Ocultar secciones vacías
        document.querySelectorAll('.faculty-section').forEach(section => {
            const grid = section.querySelector('.careers-grid');
            if (grid.children.length === 0) {
                section.style.display = 'none';
            }
        });
    }
    
    function createCareerCard(career) {
        const riasecColors = {
            'Realista': 'from-blue-500 to-blue-600',
            'Investigador': 'from-purple-500 to-purple-600',
            'Artístico': 'from-pink-500 to-pink-600',
            'Social': 'from-green-500 to-green-600',
            'Emprendedor': 'from-orange-500 to-orange-600',
            'Convencional': 'from-gray-500 to-gray-600'
        };
        
        const gradient = riasecColors[career.riasec_profile] || 'from-indigo-500 to-indigo-600';
        
        return `
            <div class="module-card career-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-gradient-to-br ${gradient} rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">${career.riasec_profile}</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">${career.name}</h4>
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">${career.description}</p>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Salario: S/. ${career.average_salary.toLocaleString()}
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Duración: ${career.duration_years} años
                    </div>
                </div>
                <button onclick="showCareerDetails(${career.id})" class="btn-secondary w-full text-sm">Ver Detalles</button>
            </div>
        `;
    }
    
    // Filtros
    const searchInput = document.getElementById('searchInput');
    const facultyFilter = document.getElementById('facultyFilter');
    
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedFaculty = facultyFilter.value;
        
        const filtered = careers.filter(career => {
            const matchesSearch = career.name.toLowerCase().includes(searchTerm) || 
                                career.description.toLowerCase().includes(searchTerm);
            const matchesFaculty = !selectedFaculty || career.faculty === selectedFaculty;
            
            return matchesSearch && matchesFaculty;
        });
        
        renderCareers(filtered);
    }
    
    searchInput.addEventListener('input', applyFilters);
    facultyFilter.addEventListener('change', applyFilters);
    
    // Renderizar carreras inicialmente
    renderCareers();
});

function showCareerDetails(careerId) {
    // Aquí puedes implementar un modal o redireccionar a una página de detalles
    alert('Detalles de la carrera ID: ' + careerId);
}
</script>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
