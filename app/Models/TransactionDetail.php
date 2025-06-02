<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function testimony()
    {
        return $this->hasOne(Testimonial::class, "transaction_detail_id");
    }

    public function product_type_stock()
    {
        return $this->belongsTo(ProductTypeStock::class);
    }

    public function getTransactionDetailKeyAttribute(){}

    public static function get_available_testimony(){}
}
