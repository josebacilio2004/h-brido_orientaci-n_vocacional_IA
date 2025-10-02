from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
import joblib
import numpy as np
from typing import List, Dict
import os

# Inicializar FastAPI
app = FastAPI(
    title="API de Predicción Vocacional",
    description="API para predecir carreras universitarias basadas en notas académicas",
    version="1.0.0"
)

# Configurar CORS para permitir peticiones desde Laravel
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # En producción, especifica el dominio de tu app Laravel
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Modelo de entrada
class EstudianteNotas(BaseModel):
    Nota_Matematica: int = Field(..., ge=0, le=20, description="Nota de Matemática (0-20)")
    Nota_Comunicacion: int = Field(..., ge=0, le=20, description="Nota de Comunicación (0-20)")
    Nota_Ciencias_Sociales: int = Field(..., ge=0, le=20, description="Nota de Ciencias Sociales (0-20)")
    Nota_Ciencia_Tecnologia: int = Field(..., ge=0, le=20, description="Nota de Ciencia y Tecnología (0-20)")
    Nota_Desarrollo_Personal: int = Field(..., ge=0, le=20, description="Nota de Desarrollo Personal (0-20)")
    Nota_Ciudadania_Civica: int = Field(..., ge=0, le=20, description="Nota de Ciudadanía y Cívica (0-20)")
    Nota_Educacion_Fisica: int = Field(..., ge=0, le=20, description="Nota de Educación Física (0-20)")
    Nota_Ingles: int = Field(..., ge=0, le=20, description="Nota de Inglés (0-20)")
    Nota_Educacion_Trabajo: int = Field(..., ge=0, le=20, description="Nota de Educación para el Trabajo (0-20)")

# Diccionario de carreras (ajustar según tu modelo entrenado)
CODIGO_CARRERAS = {
    0: "Ingeniería de Sistemas e Informática",
    1: "Ingeniería Civil",
    2: "Medicina Humana",
    3: "Derecho y Ciencias Políticas",
    4: "Administración de Empresas",
    5: "Contabilidad y Finanzas",
    6: "Psicología",
    7: "Arquitectura",
    8: "Ingeniería Industrial",
    9: "Educación",
    10: "Enfermería",
    11: "Ingeniería Electrónica",
    12: "Economía",
    13: "Comunicación Social",
    14: "Biología",
    15: "Ingeniería Mecánica"
}

# Cargar modelo (asegúrate de tener el archivo modelo_vocacional.pkl)
try:
    modelo = joblib.load("modelo_vocacional.pkl")
    print("✅ Modelo cargado exitosamente")
except FileNotFoundError:
    print("⚠️ Advertencia: modelo_vocacional.pkl no encontrado. Usando predicciones simuladas.")
    modelo = None

@app.get("/")
def root():
    """Endpoint raíz para verificar que la API está funcionando"""
    return {
        "message": "API de Predicción Vocacional",
        "version": "1.0.0",
        "status": "online",
        "modelo_cargado": modelo is not None
    }

@app.get("/health")
def health_check():
    """Endpoint de salud para monitoreo"""
    return {
        "status": "healthy",
        "modelo_disponible": modelo is not None
    }

@app.post("/predict")
def predict(estudiante: EstudianteNotas):
    """
    Predice la carrera universitaria más adecuada basándose en las notas académicas
    """
    try:
        # Convertir datos a array numpy
        X = np.array([[
            estudiante.Nota_Matematica,
            estudiante.Nota_Comunicacion,
            estudiante.Nota_Ciencias_Sociales,
            estudiante.Nota_Ciencia_Tecnologia,
            estudiante.Nota_Desarrollo_Personal,
            estudiante.Nota_Ciudadania_Civica,
            estudiante.Nota_Educacion_Fisica,
            estudiante.Nota_Ingles,
            estudiante.Nota_Educacion_Trabajo
        ]])
        
        if modelo is not None:
            # Predicción con el modelo real
            y_pred = modelo.predict(X)[0]
            
            # Obtener probabilidades si el modelo lo soporta
            if hasattr(modelo, 'predict_proba'):
                probas = modelo.predict_proba(X)[0]
                confidence = float(probas[y_pred] * 100)
                
                # Top 3 carreras
                top_indices = np.argsort(probas)[-3:][::-1]
                top_careers = [
                    {
                        "carrera": CODIGO_CARRERAS.get(idx, "No definida"),
                        "probabilidad": float(probas[idx] * 100)
                    }
                    for idx in top_indices
                ]
            else:
                confidence = 85.0  # Valor por defecto
                top_careers = [{"carrera": CODIGO_CARRERAS.get(y_pred, "No definida"), "probabilidad": 85.0}]
        else:
            # Predicción simulada basada en la nota más alta
            notas = [
                (estudiante.Nota_Matematica, 0),  # Ingeniería de Sistemas
                (estudiante.Nota_Ciencia_Tecnologia, 1),  # Ingeniería Civil
                (estudiante.Nota_Ciencias_Sociales, 2),  # Medicina
                (estudiante.Nota_Comunicacion, 3),  # Derecho
                (estudiante.Nota_Desarrollo_Personal, 6),  # Psicología
            ]
            notas.sort(reverse=True)
            y_pred = notas[0][1]
            confidence = 75.0
            top_careers = [
                {"carrera": CODIGO_CARRERAS.get(notas[i][1], "No definida"), "probabilidad": 75.0 - (i * 10)}
                for i in range(min(3, len(notas)))
            ]
        
        carrera_recomendada = CODIGO_CARRERAS.get(y_pred, "No definida")
        
        return {
            "carrera_recomendada": carrera_recomendada,
            "confidence": round(confidence, 2),
            "top_careers": top_careers,
            "model_version": "1.0",
            "input_data": estudiante.dict()
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Error en la predicción: {str(e)}")

@app.get("/carreras")
def listar_carreras():
    """Lista todas las carreras disponibles en el modelo"""
    return {
        "total": len(CODIGO_CARRERAS),
        "carreras": [{"codigo": k, "nombre": v} for k, v in CODIGO_CARRERAS.items()]
    }

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
