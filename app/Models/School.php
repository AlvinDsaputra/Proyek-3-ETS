<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = ['nama_sekolah', 'alamat'];

    public function distributions(): HasMany
    {
        return $this->hasMany(MealDistribution::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}