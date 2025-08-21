<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * DisposisiCuti Model
 * 
 * Represents individual approval steps in the surat cuti workflow.
 * Each disposisi is assigned to a specific user and tracks the approval status.
 * 
 * @property int $id
 * @property int $surat_cuti_id
 * @property int $user_id
 * @property string $jabatan
 * @property string $status
 * @property string $tipe_disposisi
 * @property \Carbon\Carbon|null $tanggal
 * @property string|null $catatan
 */
class DisposisiCuti extends Model
{
    use HasFactory;

    protected $table = 'disposisi_cuti';

    protected $fillable = [
        'surat_cuti_id',
        'user_id',
        'jabatan',
        'status',
        'tipe_disposisi',
        'tanggal',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'surat_cuti_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Status constants
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUDAH = 'sudah';

    /**
     * Signature type constants
     */
    public const TIPE_PARAF = 'paraf';
    public const TIPE_TTD = 'ttd';

    /**
     * Relationship: DisposisiCuti belongs to SuratCuti
     */
    public function suratCuti(): BelongsTo
    {
        return $this->belongsTo(SuratCuti::class);
    }

    /**
     * Relationship: DisposisiCuti belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get pending disposisi
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Get completed disposisi
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SUDAH);
    }

    /**
     * Scope: Get disposisi for specific user
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get disposisi for specific surat cuti
     */
    public function scopeForSuratCuti(Builder $query, int $suratCutiId): Builder
    {
        return $query->where('surat_cuti_id', $suratCutiId);
    }

    /**
     * Check if disposisi is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if disposisi is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_SUDAH;
    }

    /**
     * Check if this is a signature (TTD) disposisi
     */
    public function isSignature(): bool
    {
        return $this->tipe_disposisi === self::TIPE_TTD;
    }

    /**
     * Check if this is a paraf disposisi
     */
    public function isParaf(): bool
    {
        return $this->tipe_disposisi === self::TIPE_PARAF;
    }

    /**
     * Get formatted status for display
     */
    public function getFormattedStatusAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_SUDAH => 'Selesai',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get formatted signature type for display
     */
    public function getFormattedTipeDisposisiAttribute(): string
    {
        return match($this->tipe_disposisi) {
            self::TIPE_PARAF => 'Paraf',
            self::TIPE_TTD => 'Tanda Tangan',
            default => ucfirst($this->tipe_disposisi)
        };
    }
}