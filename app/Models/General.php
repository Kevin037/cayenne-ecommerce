<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class General extends Model
{
    use HasFactory;

    public static function get_monthly_customer(){
    }

    public static function get_monthly_total_transaction(){
    }

    public static function get_monthly_chart_transaction(){
    }

    public static function get_monthly_product_sale_transaction(){
    }

    public static function get_monthly_gross_profit_transaction(){
    }
    
    public static function get_monthly_progress_gross_profit_transaction(){
    }

    public static function get_monthly_nett_profit_transaction(){
    }

    public static function get_monthly_progress_nett_profit_transaction(){
    }
}
