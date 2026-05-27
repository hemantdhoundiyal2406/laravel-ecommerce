<?php

namespace App\Models;

use App\Support\Seo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'regular_price',
        'sale_price',
        'stock_quantity',
        'short_description',
        'description',
        'specifications',
        'is_featured',
        'is_best_seller',
        'is_new_arrival',
        'is_active',
        'views',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'specifications' => 'array',
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getPriceAttribute(): float
    {
        return (float) ($this->sale_price ?: $this->regular_price);
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->sale_price || $this->regular_price <= 0) {
            return 0;
        }

        return (int) round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->approvedReviews()->avg('rating'), 1);
    }

    public function getImageUrlAttribute(): string
    {
        $image = $this->primaryImage?->image_path ?: $this->images->first()?->image_path;

        return Seo::url($image);
    }

    public function getImageAltAttribute(): string
    {
        $image = $this->primaryImage ?: $this->images->first();

        return $image?->alt ?: Seo::imageAlt(null, $this->name);
    }
}
