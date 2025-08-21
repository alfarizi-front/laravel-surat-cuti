<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * AlurCuti Model
 * 
 * Defines the approval workflow for different organizational units.
 * Each unit has its own sequence of approvals with specific roles and signature types.
 * 
 * @property int $id
 * @property string $unit_kerja
 * @property int $step_ke
 * @property string $jabatan
 * @property string $tipe_disposisi
 * @property int $urutan
 */
class AlurCuti extends Model
{
    use HasFactory;

    protected $table = 'alur_cuti';

    protected $fillable = [
        'unit_kerja',
        'step_ke',
        'jabatan',
        'tipe_disposisi',
        'urutan',
    ];

    protected $casts = [
        'step_ke' => 'integer',
        'urutan' => 'integer',
    ];

    /**
     * Signature types
     */
    public const TIPE_PARAF = 'paraf';
    public const TIPE_TTD = 'ttd';

    /**
     * Get approval workflow for specific unit
     */
    public static function getAlurByUnitKerja(string $unitKerja): Collection
    {
        return self::where('unit_kerja', $unitKerja)
                   ->orderBy('urutan')
                   ->get();
    }

    /**
     * Get all available units with workflows
     */
    public static function getAvailableUnits(): Collection
    {
        return self::select('unit_kerja')
                   ->distinct()
                   ->orderBy('unit_kerja')
                   ->pluck('unit_kerja');
    }

    /**
     * Check if signature type is valid
     */
    public static function isValidTipeDisposisi(string $tipe): bool
    {
        return in_array($tipe, [self::TIPE_PARAF, self::TIPE_TTD]);
    }

    /**
     * Get workflow steps count for unit
     */
    public static function getStepsCount(string $unitKerja): int
    {
        return self::where('unit_kerja', $unitKerja)->count();
    }
}