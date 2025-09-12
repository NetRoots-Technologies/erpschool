<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Currencies extends Model
{
    protected $table = "currencies";

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'decimal_fixed_point',
        'rate',
        'is_default',
        'description',
        'status'
    ];
    //    public static function boot() {
//        parent::boot();
//        static::updating(function($table)  {
//            $table->updated_by = Auth::user()->id;
//        });
//        static::deleting(function($table)  {
//            $table->deleted_by = Auth::user()->id;
//        });
//        static::saving(function($table)  {
//            $table->created_by = Auth::user()->id;
//        });
//    }
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function onlyIdandName()
    {
        return self::where('status', 1)->get(['id', 'name']);
    }
    public static function pluckActiveOnly()
    {
        return self::where(['status' => 1])->get()->pluck('code', 'id');
    }
    //    public function CreateBy(){
//        return $this->belongsTo("App\User",'created_by')->select(['id','name']);
//    }
//    public function ApproveBy(){
//        return $this->hasOne("App\User",'id','approve_by')->select(['id','name']);
//    }
    static function getDefaultCurrencty()
    {
        return self::where(['is_default' => 1])->first();
    }
    static function curr_dec_format($amount = 0, $id = 0)
    {
        $decimal = self::where('id', $id)->value('decimal_fixed_point');

        return floor($amount) . substr(str_replace(floor($amount), '', $amount), 0, $decimal + 3);



    }

}
