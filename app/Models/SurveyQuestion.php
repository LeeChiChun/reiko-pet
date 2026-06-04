<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    protected $fillable = ['title', 'description', 'type', 'options', 'sort_order', 'is_active'];

    protected $casts = [
        'options'   => 'array',
        'is_active' => 'boolean',
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class, 'question_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function averageStarRating(): float
    {
        if ($this->type !== 'star') return 0;
        return (float) $this->answers()->avg('answer') ?: 0;
    }
}
