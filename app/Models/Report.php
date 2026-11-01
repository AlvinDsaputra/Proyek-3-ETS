<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'meal_distribution_id',
        'tanggal_kejadian',
        'deskripsi_kejadian',
        'jumlah_terdampak',
        'bukti_file',
        'status',
        'catatan_pemantau',
        'diteruskan_ke',
        'tanggal_diteruskan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(MealDistribution::class, 'meal_distribution_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ReportLog::class);
    }
}