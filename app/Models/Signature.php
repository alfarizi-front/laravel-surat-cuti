<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $fillable = [
        'jabatan',
        'nama',
        'nip',
        'signature_path',
        'stamp_path',
        'is_active',
        'keterangan'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get signature by position/jabatan
     */
    public static function getByJabatan($jabatan)
    {
        return self::where('jabatan', $jabatan)
                   ->where('is_active', true)
                   ->first();
    }

    /**
     * Get signature image URL
     */
    public function getSignatureUrl()
    {
        if ($this->signature_path && file_exists(public_path($this->signature_path))) {
            return asset($this->signature_path);
        }
        return null;
    }

    /**
     * Get stamp image URL
     */
    public function getStampUrl()
    {
        if ($this->stamp_path && file_exists(public_path($this->stamp_path))) {
            return asset($this->stamp_path);
        }
        return null;
    }

    /**
     * Check if signature has both signature and stamp
     */
    public function hasCompleteSignature()
    {
        return $this->signature_path && $this->stamp_path;
    }
}
