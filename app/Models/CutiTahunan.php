<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiTahunan extends Model
{
    use HasFactory;

    protected $table = 'cuti_tahunan';

    protected $fillable = [
        'user_id',
        'tahun',
        'jatah_cuti',
        'cuti_digunakan',
        'cuti_pending',
        'sisa_cuti'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jatah_cuti' => 'integer',
        'cuti_digunakan' => 'integer',
        'cuti_pending' => 'integer',
        'sisa_cuti' => 'integer'
    ];

    /**
     * Relationship dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get atau create record cuti tahunan untuk user dan tahun tertentu
     */
    public static function getOrCreateForUser($userId, $tahun = null)
    {
        $tahun = $tahun ?? date('Y');
        
        return self::firstOrCreate(
            ['user_id' => $userId, 'tahun' => $tahun],
            [
                'jatah_cuti' => 12,
                'cuti_digunakan' => 0,
                'cuti_pending' => 0,
                'sisa_cuti' => 12
            ]
        );
    }

    /**
     * Update sisa cuti berdasarkan cuti yang digunakan dan pending
     */
    public function updateSisaCuti()
    {
        $this->sisa_cuti = $this->jatah_cuti - $this->cuti_digunakan - $this->cuti_pending;
        $this->save();
    }

    /**
     * Tambah cuti pending
     */
    public function addPendingCuti($jumlahHari)
    {
        $this->cuti_pending += $jumlahHari;
        $this->updateSisaCuti();
    }

    /**
     * Approve cuti (pindah dari pending ke digunakan)
     */
    public function approveCuti($jumlahHari)
    {
        $this->cuti_pending -= $jumlahHari;
        $this->cuti_digunakan += $jumlahHari;
        $this->updateSisaCuti();
    }

    /**
     * Reject cuti (hapus dari pending)
     */
    public function rejectCuti($jumlahHari)
    {
        $this->cuti_pending -= $jumlahHari;
        $this->updateSisaCuti();
    }

    /**
     * Cek apakah masih ada sisa cuti
     */
    public function hasSisaCuti($jumlahHari = 1)
    {
        return $this->sisa_cuti >= $jumlahHari;
    }

    /**
     * Cek apakah pengajuan melebihi batas maksimal
     */
    public function isExceedingLimit($jumlahHari)
    {
        return ($this->cuti_digunakan + $this->cuti_pending + $jumlahHari) > $this->jatah_cuti;
    }

    /**
     * Get total cuti yang akan digunakan jika pengajuan ini disetujui
     */
    public function getTotalCutiIfApproved($jumlahHari)
    {
        return $this->cuti_digunakan + $this->cuti_pending + $jumlahHari;
    }

    /**
     * Get persentase penggunaan cuti
     */
    public function getPersentasePenggunaan()
    {
        $totalDigunakan = $this->cuti_digunakan + $this->cuti_pending;
        return ($totalDigunakan / $this->jatah_cuti) * 100;
    }
}
