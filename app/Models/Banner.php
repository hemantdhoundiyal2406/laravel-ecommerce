<?php

namespace App\Models;

use App\Support\Seo;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'button_text',
        'button_link',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        return Seo::url($this->image);
    }

    public function getImageAltAttribute(): string
    {
        return Seo::imageAlt($this->image, $this->title);
    }
}
