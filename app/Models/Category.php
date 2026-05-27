<?php

namespace App\Models;

use App\Support\Seo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'image',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute(): string
    {
        return Seo::url($this->image);
    }

    public function getImageAltAttribute(): string
    {
        return Seo::imageAlt($this->image, $this->name);
    }
}
