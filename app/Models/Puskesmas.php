<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puskesmas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_puskesmas',
        'kepala_puskesmas',
        'nip_kepala',
        'tanda_tangan',
        'cap_stempel',
    ];
}
