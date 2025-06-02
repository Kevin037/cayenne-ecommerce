<?php

namespace App\Models;

use App\Filament\Resources\ProductResource\RelationManagers\ProductTypeStocksRelationManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const img_path = 'storage';

    public function getPhotoAttribute($value)
    {
        return asset($this::img_path) . "/" . $value;
    }

    public function photos(){
        return $this->hasMany(ProductPhoto::class)->orderBy('ordering','desc');
    }

    public function testimonials(){
        return $this->hasMany(Testimonial::class);
    }

    public function types(){
        return $this->hasMany(ProductType::class)->orderBy('type_id','asc')->orderBy('id','asc');
    }

    public function stocks(){
        return $this->hasMany(ProductTypeStock::class);
    }

    public function category(){}

    public static function get_options($id){}

    public function get_promotion(){}

    public function getPercentageDiscountAttribute(){}

    public function getNewPriceAttribute(){}

    public function get_new_price($price){}

    public function getSoldAttribute(){}

    public function getIsAvailableStockAttribute(){}

    public function get_group_types(){}
    public static function get_photo($id){}

    public static function check_duplicate($name, $column, $id = null){}

    public function getCategoryNameAttribute(){}

    public static function get_reccomendation_list(){}

    public static function get_best_seller_list(){}

    public function getIsTypesAttribute(){
        return (count($this->stocks) > 0) ? "Ya" : "";
    }
}
