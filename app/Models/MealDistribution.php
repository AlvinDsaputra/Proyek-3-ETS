<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealDistribution extends Model
{
    protected $fillable = ['school_id', 'tanggal_distribusi', 'menu_makanan', 'sppg_penyedia'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}