<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SebastianBergmann\CodeCoverage\StaticAnalysisCacheNotConfiguredException;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment()
    {}

    public function delivery()
    {
        return $this->hasOne(Delivery::class, "transaction_id");
    }

    public function details(){
        return $this->hasMany(TransactionDetail::class);
    }

    public static function get_couriers(){}

    public static function get_status($status){
        foreach (Transaction::get_status_options() as $key => $value) {
            if ($status == $key) {
                return $value;
            }
        }
    }

    public function getCustomerNameAttribute(){
        return ($this->customer_id != null) ? $this->customer->name : "";
    }

    public function getQtyAttribute(){}

    public static function get_status_class($status){}

    public static function get_status_options(){
        $list = [
            'pending' => 'Pending',
            'cancelled' => 'Dibatalkan',
            'paid' => 'Dibayar',
            'process' => 'Diproses',
            'sending' => 'Dikirim',
            'completed' => 'Selesai',
        ];
        return $list;
    }

    public static function get_no($phone){}

    public static function get_each_product_formulation($data, $chart_id=null){}

    public static function get_product_formulation($request){}

    public function getHppAttribute(){}

    public static function cek_stok($products){}
}
