<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCuti extends Model
{
    use HasFactory;

    protected $table = 'jenis_cuti';

    protected $fillable = [
        'nama',
        'berlaku_untuk',
    ];

    /**
     * Relationship: JenisCuti has many surat cuti
     */
    public function suratCuti()
    {
        return $this->hasMany(SuratCuti::class);
    }
}
