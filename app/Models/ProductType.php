<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const img_path = 'storage';

    public function getPhotoAttribute($value)
    {
        return asset($this::img_path) . "/" . $value;
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function get_name($arr){
        
    }
}
