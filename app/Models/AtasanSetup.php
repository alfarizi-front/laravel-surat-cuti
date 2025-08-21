<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtasanSetup extends Model
{
    use HasFactory;

    protected $table = 'atasan_setup';

    protected $fillable = [
        'jenis_pegawai',
        'level_atasan',
        'nama_jabatan',
        'perlu_tanda_tangan',
        'perlu_cap_stempel',
        'urutan_disposisi',
        'keterangan',
        'aktif'
    ];

    protected $casts = [
        'perlu_tanda_tangan' => 'boolean',
        'perlu_cap_stempel' => 'boolean',
        'aktif' => 'boolean'
    ];

    /**
     * Get atasan setup for specific jenis pegawai
     */
    public static function getAtasanForJenisPegawai($jenisPegawai)
    {
        return self::where('jenis_pegawai', $jenisPegawai)
                   ->where('aktif', true)
                   ->orderBy('urutan_disposisi')
                   ->get();
    }

    /**
     * Get users who need signature for specific setup
     */
    public function getUsersWhoNeedSignature()
    {
        if (!$this->perlu_tanda_tangan) {
            return collect();
        }

        return User::where('role', $this->level_atasan)
                   ->orWhere('jabatan', 'like', '%' . $this->nama_jabatan . '%')
                   ->get();
    }

    /**
     * Check if this atasan needs cap stempel
     */
    public function needsCapStempel()
    {
        return $this->perlu_cap_stempel;
    }

    /**
     * Get template type based on jenis pegawai
     */
    public static function getTemplateType($jenisPegawai)
    {
        $setup = self::where('jenis_pegawai', $jenisPegawai)
                     ->where('aktif', true)
                     ->first();
        
        return $setup ? $jenisPegawai : 'default';
    }
}
