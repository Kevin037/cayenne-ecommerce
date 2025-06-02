<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\StaticVar;

class ProductTypeStock extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function get_product_type_name($arr){}

    public function get_product_type_stock_name(){}

    public static function get_detail($product_type_ids, $product_id){}

    public function getNewPriceAttribute()
    {
        return ($this->price != null) ? $this->product->get_new_price($this->price) : $this->product->new_price;
    }
}
