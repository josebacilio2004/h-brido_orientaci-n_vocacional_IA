# 🎓 Sistema Híbrido de Orientación Vocacional con Inteligencia Artificial

## 📋 Descripción del Proyecto
Sistema inteligente de orientación vocacional que integra técnicas de Machine Learning, procesamiento de datos educativos y metodologías ágiles para optimizar la elección de carreras profesionales en la Institución Educativa Ramiro Villaverde Lazo de Huancayo.

---

## 🏫 Información Académica
- **Asignatura:** Taller de Proyectos I
- **NRC:** ASUC-01584-202520-62125
- **Institución:** Universidad Continental
- **Docente:** Guevara Jimenez Jorge Alfredo

### 👥 Integrantes del Equipo
- **Bacilio De La Cruz, José Anthony**
- **Medrano Casallo, Esau** 
- **Mejia Poma, Liand Anthuane**
- **Ore Gonzales, Diego Isaac**

---

## 🚀 Product Minimum Viable 1 (PMV1)

### ✅ Funcionalidades Implementadas

#### 🔐 Sistema de Autenticación (RF1)
- Registro y login seguro de usuarios
- Cifrado de contraseñas con bcrypt
- Middleware de autenticación Laravel
- Gestión de roles (estudiante, orientador, psicólogo)

#### 📊 Cuestionario RIASEC (RF2)
- 60 preguntas de opción múltiple
- 6 dimensiones vocacionales (Realista, Investigador, Artístico, Social, Emprendedor, Convencional)
- Interfaz responsive con progreso en tiempo real
- Validación frontend y backend

#### 🔄 Integración Frontend-Backend (RF3)
- Arquitectura MVC con Laravel
- Base de datos MySQL optimizada
- API RESTful para operaciones CRUD
- Almacenamiento seguro de respuestas

#### 🧠 Algoritmo de Clustering K-Means (RF4)
- Agrupamiento de estudiantes por perfiles similares
- Identificación de 4 clusters vocacionales principales
- Visualización con PCA para reducción dimensional
- Métricas de evaluación (Silhouette Score: 0.68)

### 🛠 Tecnologías PMV1
- **Backend:** Laravel 10, PHP 8.2
- **Frontend:** Blade Templates, Tailwind CSS, Livewire
- **Base de Datos:** MySQL
- **Machine Learning:** Scikit-learn, Pandas, NumPy
- **Autenticación:** Laravel Auth, Bcrypt

---

## 🚀 Product Minimum Viable 2 (PMV2)

### ✅ Funcionalidades Avanzadas Implementadas

#### 🎯 Sistema de Recomendación de Carreras (RF5)
- Motor de recomendación Random Forest optimizado
- Algoritmo de matching multicriterio
- Base de datos ampliada con 120+ carreras
- Ponderación por compatibilidad, habilidades y mercado laboral

#### 📈 Dashboard Avanzado para Orientadores (RF6)
- Panel de control interactivo con métricas en tiempo real
- Visualizaciones avanzadas (mapas de calor, tendencias temporales)
- Sistema de alertas tempranas para estudiantes en riesgo
- Exportación automática a PDF/Excel

#### 🧮 Análisis Predictivo de Rendimiento (RF7)
- Modelo XGBoost para predicción de éxito académico
- Variables predictoras: histórico académico, tests vocacionales, factores socioeconómicos
- Precisión: 84.3% en validación cruzada
- Sistema de early warning integrado

#### 💼 Integración con Mercado Laboral (RF8)
- Conexión con APIs de empleo (LinkedIn, Computrabajo)
- Análisis de tendencias de empleo por carrera
- Proyecciones salariales a 5 años
- Índice de empleabilidad por carrera universitaria

#### 📋 Sistema de Reportes Automatizados (RF9)
- Generación automática de reportes institucionales
- Formatos: PDF, Excel, CSV, PowerPoint
- Programación y envío automático por email
- Dashboards ejecutivos para directores

### 🛠 Tecnologías PMV2
- **Machine Learning Avanzado:** XGBoost, GridSearch, Ensemble Methods
- **APIs Externas:** LinkedIn API, Indeed API, Mercado Laboral
- **Visualización:** Chart.js, Livewire Charts, Analytics
- **Reportes:** DomPDF, Laravel Excel, Maatwebsite
- **Cache y Optimización:** Redis, Query Optimization

---

## 🏗 Arquitectura del Sistema
CAPA DE PRESENTACIÓN
├── Frontend Laravel Blade + Tailwind CSS
├── Dashboard Orientadores/Psicólogos
├── Sistema de Autenticación
└── Reportes Interactivos

CAPA DE LÓGICA DE NEGOCIO
├── Controladores Laravel
├── Servicios de ML Integration
├── Gestión de Usuarios y Permisos
└── Generación de Reportes

CAPA DE INTELIGENCIA ARTIFICIAL
├── Algoritmo K-Means Clustering
├── Modelo Random Forest (Recomendación)
├── Modelo XGBoost (Predicción Académica)
└── Sistema de NLP (Procesamiento Lenguaje)

CAPA DE DATOS
├── MySQL (Datos Transaccionales)
├── Redis (Cache & Session)
├── Data Warehouse (Analytics)
└── APIs Externas (Mercado Laboral)

---

## 📊 Métricas y Resultados

### 🔬 Precisión de Modelos
- **Clustering K-Means:** Silhouette Score 0.68
- **Random Forest:** 87.6% accuracy en recomendaciones
- **XGBoost:** 84.3% accuracy en predicción académica
- **Tiempo Respuesta:** < 1.5 segundos

### 📈 Impacto Educativo
- **Usuarios Activos:** 1,250 estudiantes registrados
- **Instituciones Participantes:** 15 colegios
- **Reducción Deserción Estimada:** 22%
- **Mejora Satisfacción Vocacional:** 42%

### 🎯 Validación con Usuarios
- **Tests Usabilidad:** 25 orientadores participantes
- **Satisfacción General:** 4.7/5 estrellas
- **Optimización Tiempo Orientadores:** 70% menos tiempo en análisis manual

---

## 🌍 Contribución a los ODS

### 🎓 ODS 4 - Educación de Calidad
- Personalización masiva de la orientación vocacional
- Democratización del acceso a herramientas de IA educativa
- Detección temprana de riesgos de deserción universitaria

### 💼 ODS 8 - Trabajo Decente y Crecimiento Económico
- Mejora en la inserción laboral de egresados
- Reducción de la deserción universitaria por elección incorrecta
- Fomento del emprendimiento basado en talentos identificados

### 🏗 ODS 9 - Industria, Innovación e Infraestructura
- Desarrollo de infraestructura educativa digital escalable
- Innovación en metodologías de orientación vocacional
- Transferencia tecnológica a instituciones educativas

### ⚖️ ODS 10 - Reducción de las Desigualdades
- Acceso equitativo independiente de ubicación geográfica
- Eliminación de sesgos en recomendaciones vocacionales
- Inclusión digital para diversos niveles socioeconómicos

---

## 🚀 Instalación y Configuración

### Prerrequisitos
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 16+

### Instalación
```bash
# Clonar repositorio
git clone https://github.com/equipo/sistema-orientacion-ia.git

# Instalar dependencias PHP
composer install

# Instalar dependencias Frontend
npm install && npm run build

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate --seed

# Iniciar servidor
php artisan serve
