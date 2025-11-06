#!/usr/bin/env python3
"""
Advanced Random Forest Career Prediction Model
Predice carreras recomendadas basadas en características del estudiante
incluyendo notas académicas, tests vocacionales y análisis de habilidades
"""

import json
import sys
import numpy as np
from sklearn.ensemble import RandomForestClassifier, GradientBoostingClassifier
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score, confusion_matrix
import pickle
import os
import warnings

warnings.filterwarnings('ignore')

class AdvancedCareerPredictionModel:
    def __init__(self, model_path='storage/app/ml-models/career_model.pkl'):
        self.model_path = model_path
        self.model = None
        self.scaler = StandardScaler()
        self.label_encoder = LabelEncoder()
        self.feature_names = [
            # Academic Grades (normalized)
            'nota_matematica', 'nota_comunicacion', 'nota_ciencias_sociales',
            'nota_ciencia_tecnologia', 'nota_desarrollo_personal',
            'nota_ciudadania_civica', 'nota_educacion_fisica', 'nota_ingles',
            'nota_educacion_trabajo',
            # Test Scores
            'riasec_realista', 'riasec_investigador', 'riasec_artistico',
            'riasec_social', 'riasec_emprendedor', 'riasec_convencional',
            'interest_score_avg', 'skill_score_avg', 'personality_score_avg',
            # Performance Indicators
            'gpa', 'engagement_level_numeric', 'academic_performance_numeric'
        ]
        self.performance_metrics = {
            'accuracy': 0.0,
            'precision': 0.0,
            'recall': 0.0,
            'f1_score': 0.0,
            'confusion_matrix': []
        }

    def extract_numeric_features(self, features):
        """Extraer características numéricas enriquecidas de los datos del estudiante"""
        try:
            numeric_features = [
                # Notas académicas (normalizadas a 0-1)
                float(features.get('nota_matematica', 0)) / 20.0,
                float(features.get('nota_comunicacion', 0)) / 20.0,
                float(features.get('nota_ciencias_sociales', 0)) / 20.0,
                float(features.get('nota_ciencia_tecnologia', 0)) / 20.0,
                float(features.get('nota_desarrollo_personal', 0)) / 20.0,
                float(features.get('nota_ciudadania_civica', 0)) / 20.0,
                float(features.get('nota_educacion_fisica', 0)) / 20.0,
                float(features.get('nota_ingles', 0)) / 20.0,
                float(features.get('nota_educacion_trabajo', 0)) / 20.0,
                # RIASEC Scores
                float(features.get('riasec_realista', 0)) / 10.0,
                float(features.get('riasec_investigador', 0)) / 10.0,
                float(features.get('riasec_artistico', 0)) / 10.0,
                float(features.get('riasec_social', 0)) / 10.0,
                float(features.get('riasec_emprendedor', 0)) / 10.0,
                float(features.get('riasec_convencional', 0)) / 10.0,
                # Test Averages
                np.mean(features.get('interest_scores', [0.5])),
                np.mean(features.get('skill_scores', [0.5])),
                np.mean(features.get('personality_traits', [0.5])),
                # Overall Performance
                float(features.get('gpa', 0)) / 20.0,
                self._engagement_to_numeric(features.get('engagement_level', 'unknown')),
                self._performance_to_numeric(features.get('academic_performance', 'average')),
            ]
            
            # Ensure all values are valid
            numeric_features = [max(0, min(1, float(x))) for x in numeric_features]
            return np.array(numeric_features).reshape(1, -1)
        except Exception as e:
            print(f"Error extracting features: {e}", file=sys.stderr)
            return None

    def _engagement_to_numeric(self, level):
        """Convertir nivel de engagement a valor numérico"""
        engagement_map = {'high': 1.0, 'medium': 0.6, 'low': 0.3, 'none': 0.0}
        return engagement_map.get(level, 0.5)

    def _performance_to_numeric(self, level):
        """Convertir nivel de rendimiento académico a valor numérico"""
        performance_map = {
            'excellent': 1.0,
            'very_good': 0.85,
            'good': 0.7,
            'average': 0.5,
            'below_average': 0.3
        }
        return performance_map.get(level, 0.5)

    def predict(self, features):
        """Generar predicción de carreras"""
        try:
            if not self.model_exists():
                return self.get_default_prediction()

            with open(self.model_path, 'rb') as f:
                model_data = pickle.load(f)
                self.model = model_data.get('model')
                scaler = model_data.get('scaler')

            numeric_features = self.extract_numeric_features(features)
            if numeric_features is None:
                return self.get_default_prediction()

            # Aplicar scaling
            if scaler:
                numeric_features = scaler.transform(numeric_features)

            # Realizar predicción
            if hasattr(self.model, 'predict_proba'):
                probabilities = self.model.predict_proba(numeric_features)[0]
            else:
                probabilities = self.model.predict(numeric_features)
            
            confidence = float(np.max(probabilities)) if len(probabilities) > 0 else 0.5

            return {
                'predictions': probabilities.tolist() if hasattr(probabilities, 'tolist') else probabilities,
                'confidence': confidence,
                'success': True,
                'model_version': '2.0_enhanced'
            }
        except Exception as e:
            print(f"Prediction error: {e}", file=sys.stderr)
            return self.get_default_prediction()

    def model_exists(self):
        """Verificar si el modelo entrenado existe"""
        return os.path.exists(self.model_path)

    def get_default_prediction(self):
        """Retornar predicción por defecto si el modelo no existe"""
        return {
            'predictions': [0.14] * 7,  # 7 carreras distribuidas
            'confidence': 0.5,
            'success': True,
            'warning': 'Using default prediction - model not trained yet',
            'model_version': '2.0_enhanced'
        }

    def train(self, training_data):
        """Entrenar el modelo con datos históricos mejorado"""
        try:
            X = []
            y = []

            for record in training_data:
                features = self.extract_numeric_features(record['features'])
                if features is not None:
                    X.append(features[0])
                    y.append(record['label'])

            if len(X) < 10:
                raise Exception("Insufficient training data (need at least 10 samples)")

            X = np.array(X)
            y = np.array(y)

            # Split data for validation
            X_train, X_test, y_train, y_test = train_test_split(
                X, y, test_size=0.2, random_state=42
            )

            # Scale features
            self.scaler = StandardScaler()
            X_train_scaled = self.scaler.fit_transform(X_train)
            X_test_scaled = self.scaler.transform(X_test)

            # Train ensemble of models
            self.model = GradientBoostingClassifier(
                n_estimators=200,
                learning_rate=0.05,
                max_depth=8,
                min_samples_split=5,
                min_samples_leaf=2,
                subsample=0.8,
                random_state=42
            )
            self.model.fit(X_train_scaled, y_train)

            # Calculate metrics
            y_pred = self.model.predict(X_test_scaled)
            accuracy = accuracy_score(y_test, y_pred)
            precision = precision_score(y_test, y_pred, average='weighted', zero_division=0)
            recall = recall_score(y_test, y_pred, average='weighted', zero_division=0)
            f1 = f1_score(y_test, y_pred, average='weighted', zero_division=0)

            # Save model with scaler
            os.makedirs(os.path.dirname(self.model_path), exist_ok=True)
            with open(self.model_path, 'wb') as f:
                pickle.dump({
                    'model': self.model,
                    'scaler': self.scaler,
                    'features': self.feature_names
                }, f)

            self.performance_metrics = {
                'accuracy': float(accuracy),
                'precision': float(precision),
                'recall': float(recall),
                'f1_score': float(f1),
                'confusion_matrix': confusion_matrix(y_test, y_pred).tolist()
            }

            return {
                'success': True,
                'accuracy': float(accuracy),
                'precision': float(precision),
                'recall': float(recall),
                'f1_score': float(f1),
                'samples_trained': len(X),
                'samples_test': len(X_test),
                'model_version': '2.0_enhanced',
                'confusion_matrix': confusion_matrix(y_test, y_pred).tolist()
            }
        except Exception as e:
            print(f"Training error: {e}", file=sys.stderr)
            return {
                'success': False,
                'error': str(e)
            }

def main():
    if len(sys.argv) < 2:
        print(json.dumps({'error': 'Missing input data'}), file=sys.stderr)
        sys.exit(1)

    try:
        input_data = json.loads(sys.argv[1])
    except json.JSONDecodeError:
        print(json.dumps({'error': 'Invalid JSON input'}), file=sys.stderr)
        sys.exit(1)

    model = AdvancedCareerPredictionModel()

    if '--train' in sys.argv:
        result = model.train(input_data)
    else:
        result = model.predict(input_data)

    print(json.dumps(result))

if __name__ == '__main__':
    main()
