<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Career extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'faculty',
        'university',
        'campus',
        'riasec_profile',
        'riasec_scores',
        'required_skills',
        'related_subjects',
        'average_salary',
        'duration_years',
        'job_opportunities',
        'image_url'
    ];

    protected $casts = [
        'required_skills' => 'array',
        'related_subjects' => 'array',
        'riasec_scores' => 'array',
        'average_salary' => 'decimal:2',
    ];

    // ============================================
    // MÉTODOS DAO CON STORED PROCEDURES
    // ============================================

    /**
     * Obtener todas las carreras
     */
    public static function obtenerTodas()
    {
        return DB::select('CALL sp_get_all_careers()');
    }

    /**
     * Obtener carreras por facultad
     */
    public static function obtenerPorFacultad($faculty)
    {
        return DB::select('CALL sp_get_careers_by_faculty(?)', [$faculty]);
    }

    /**
     * Obtener carreras por perfil RIASEC
     */
    public static function obtenerPorRiasec($riasecProfile)
    {
        return DB::select('CALL sp_get_careers_by_riasec(?)', [$riasecProfile]);
    }

    /**
     * Buscar carreras por término
     */
    public static function buscar($searchTerm)
    {
        return DB::select('CALL sp_search_careers(?)', [$searchTerm]);
    }

    /**
     * Obtener lista de facultades únicas
     */
    public static function obtenerFacultades()
    {
        return self::select('faculty')
            ->distinct()
            ->orderBy('faculty')
            ->pluck('faculty')
            ->toArray();
    }

    /**
     * Obtener lista de perfiles RIASEC únicos
     */
    public static function obtenerPerfilesRiasec()
    {
        return self::select('riasec_profile')
            ->distinct()
            ->orderBy('riasec_profile')
            ->pluck('riasec_profile')
            ->toArray();
    }
}
