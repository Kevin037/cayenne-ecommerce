<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const img_path = 'storage';

    public function getPhotoAttribute($value)
    {
        return asset($this::img_path) . "/" . $value;
    }

    public static function check_duplicate($name, $id = null){
        $pc = ($id == null) ? ProductCategory::where("name", $name)->count() : ProductCategory::where("id", "!=", $id)->where("name", $name)->count();
        return ($pc > 0) ? false : true;
    }

    public static function get_list_children($is_admin = false){}

    public static function get_available(){}

    public static function get_available_options($id=null){}

    public static function get_navbar_available(){}

    public static function get_sidebar_available(){}

    public static function get_children_available(){}

    public function getRelatedCategoriesAttribute(){}

    public function parent(){}

    public function getParentNameAttribute(){}

    public function get_details(){}

    public function products(){}
}
