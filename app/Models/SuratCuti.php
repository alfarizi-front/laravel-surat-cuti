<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratCuti extends Model
{
    use HasFactory;

    protected $table = 'surat_cuti';

    protected $fillable = [
        'pengaju_id',
        'golongan_ruang',
        'masa_jabatan',
        'jenis_cuti_id',
        'tanggal_awal',
        'tanggal_akhir',
        'alasan',
        'alamat_selama_cuti',
        'kontak_darurat',
        'lampiran',
        'status',
        'tanggal_ajuan',
    ];

    protected $casts = [
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
        'tanggal_ajuan' => 'datetime',
    ];

    /**
     * Boot method untuk event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Event listener untuk update sisa cuti ketika status berubah
        static::updated(function ($suratCuti) {
            // Cek apakah status berubah menjadi 'disetujui'
            if ($suratCuti->isDirty('status') && $suratCuti->status === 'disetujui') {
                \App\Models\SisaCuti::updateOnLeaveApproval($suratCuti);
            }

            // Cek apakah status berubah dari 'disetujui' ke status lain (dibatalkan)
            if ($suratCuti->isDirty('status') && $suratCuti->getOriginal('status') === 'disetujui' && $suratCuti->status !== 'disetujui') {
                \App\Models\SisaCuti::rollbackOnLeaveCancel($suratCuti);
            }
        });
    }

    /**
     * Relationship: SuratCuti belongs to User (pengaju)
     */
    public function pengaju()
    {
        return $this->belongsTo(User::class, 'pengaju_id');
    }

    /**
     * Relationship: SuratCuti belongs to JenisCuti
     */
    public function jenisCuti()
    {
        return $this->belongsTo(JenisCuti::class);
    }

    /**
     * Relationship: SuratCuti has many disposisi cuti
     */
    public function disposisiCuti()
    {
        return $this->hasMany(DisposisiCuti::class);
    }

    /**
     * Get jumlah hari cuti
     */
    public function getJumlahHariAttribute()
    {
        return $this->tanggal_awal->diffInDays($this->tanggal_akhir) + 1;
    }

    /**
     * Check if surat cuti can be processed by user
     */
    public function canBeProcessedBy(User $user)
    {
        // Get current step in disposisi
        $currentDisposisi = $this->disposisiCuti()
                                ->where('status', 'pending')
                                ->orderBy('created_at')
                                ->first();

        if (!$currentDisposisi) {
            return false;
        }

        return $currentDisposisi->user_id === $user->id;
    }
}
