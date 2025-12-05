<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'price', 
        
        // Spesifikasi
        'weight_kg', 
        'length_cm', 
        'width_cm', 
        'height_cm', 
        'material', 
        'finishing', 
        'color', 
        
        // Stok & Status
        'stock', 
        'availability', 
        'is_active', 
        'is_featured'
    ];

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}