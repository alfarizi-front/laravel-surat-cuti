<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'jabatan',
        'unit_kerja',
        'role',
        'jenis_pegawai',
        'tanda_tangan',
        'nip',
        'cap_stempel',
        'gunakan_cap',
        'golongan',
        'masa_kerja',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship: User has many surat cuti
     */
    public function suratCuti()
    {
        return $this->hasMany(SuratCuti::class, 'pengaju_id');
    }

    /**
     * Relationship: User has many disposisi cuti
     */
    public function disposisiCuti()
    {
        return $this->hasMany(DisposisiCuti::class);
    }

    /**
     * Relasi dengan SisaCuti
     */
    public function sisaCuti()
    {
        return $this->hasMany(SisaCuti::class);
    }

    /**
     * Get sisa cuti untuk tahun tertentu
     */
    public function getSisaCutiTahun($tahun)
    {
        return $this->sisaCuti()->where('tahun', $tahun)->where('is_active', true)->first();
    }

    /**
     * Relationship dengan cuti tahunan
     */
    public function cutiTahunan()
    {
        return $this->hasMany(CutiTahunan::class);
    }

    /**
     * Get cuti tahunan untuk tahun tertentu
     */
    public function getCutiTahunan($tahun = null)
    {
        $tahun = $tahun ?? date('Y');

        return CutiTahunan::getOrCreateForUser($this->id, $tahun);
    }

    /**
     * Get sisa cuti untuk tahun tertentu
     */
    public function getSisaCuti($tahun = null)
    {
        return $this->getCutiTahunan($tahun)->sisa_cuti;
    }

    /**
     * Cek apakah user masih punya sisa cuti
     */
    public function hasSisaCuti($jumlahHari = 1, $tahun = null)
    {
        return $this->getCutiTahunan($tahun)->hasSisaCuti($jumlahHari);
    }
}
