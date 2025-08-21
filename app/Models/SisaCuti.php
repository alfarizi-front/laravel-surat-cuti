<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SisaCuti extends Model
{
    protected $table = 'sisa_cuti';

    protected $fillable = [
        'user_id',
        'tahun',
        'sisa_awal',
        'cuti_diambil',
        'sisa_akhir',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'sisa_awal' => 'integer',
        'cuti_diambil' => 'integer',
        'sisa_akhir' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship dengan User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get sisa cuti untuk user dan tahun tertentu
     */
    public static function getSisaCuti($userId, $tahun)
    {
        return self::where('user_id', $userId)
                   ->where('tahun', $tahun)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Update sisa cuti setelah pengambilan cuti
     */
    public function updateSisaCuti($jumlahHari)
    {
        $this->cuti_diambil += $jumlahHari;
        $this->sisa_akhir = $this->sisa_awal - $this->cuti_diambil;
        $this->save();

        return $this;
    }

    /**
     * Reset sisa cuti (untuk awal tahun baru)
     */
    public static function resetSisaCuti($userId, $tahunBaru, $sisaBaru = 12)
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'tahun' => $tahunBaru
            ],
            [
                'sisa_awal' => $sisaBaru,
                'cuti_diambil' => 0,
                'sisa_akhir' => $sisaBaru,
                'is_active' => true
            ]
        );
    }

    /**
     * Get sisa cuti untuk beberapa tahun (untuk tampilan PDF)
     */
    public static function getSisaCutiMultiYear($userId, $tahunArray = [])
    {
        if (empty($tahunArray)) {
            $tahunArray = [2023, 2024, 2025];
        }

        $result = [];
        foreach ($tahunArray as $tahun) {
            $sisaCuti = self::getSisaCuti($userId, $tahun);
            $result[$tahun] = $sisaCuti ? $sisaCuti->sisa_akhir : null;
        }

        return $result;
    }

    /**
     * Update sisa cuti ketika surat cuti disetujui
     */
    public static function updateOnLeaveApproval($suratCuti)
    {
        $tahun = $suratCuti->tanggal_awal->year;
        $sisaCuti = self::where('user_id', $suratCuti->pengaju_id)
                       ->where('tahun', $tahun)
                       ->first();

        if ($sisaCuti) {
            $sisaCuti->cuti_diambil += $suratCuti->jumlah_hari;
            $sisaCuti->sisa_akhir = $sisaCuti->sisa_awal - $sisaCuti->cuti_diambil;
            $sisaCuti->save();

            return $sisaCuti;
        }

        // Jika belum ada record untuk tahun tersebut, buat baru
        return self::create([
            'user_id' => $suratCuti->pengaju_id,
            'tahun' => $tahun,
            'sisa_awal' => 12,
            'cuti_diambil' => $suratCuti->jumlah_hari,
            'sisa_akhir' => 12 - $suratCuti->jumlah_hari,
            'keterangan' => "Auto-created on leave approval",
            'is_active' => true
        ]);
    }

    /**
     * Rollback sisa cuti ketika surat cuti dibatalkan
     */
    public static function rollbackOnLeaveCancel($suratCuti)
    {
        $tahun = $suratCuti->tanggal_awal->year;
        $sisaCuti = self::where('user_id', $suratCuti->pengaju_id)
                       ->where('tahun', $tahun)
                       ->first();

        if ($sisaCuti && $sisaCuti->cuti_diambil >= $suratCuti->jumlah_hari) {
            $sisaCuti->cuti_diambil -= $suratCuti->jumlah_hari;
            $sisaCuti->sisa_akhir = $sisaCuti->sisa_awal - $sisaCuti->cuti_diambil;
            $sisaCuti->save();

            return $sisaCuti;
        }

        return null;
    }
}
