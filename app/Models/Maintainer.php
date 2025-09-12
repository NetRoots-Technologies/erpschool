<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Branches;

class Maintainer extends Model
{
    use HasFactory;

    public $fillable=[
        'user_id',
        'branch_id',
        'type_id',
        'profile',
        'parent_id',
    ];

    public function branches(){

        // dd($this->branch_id);
        $branch=[];
        if(!empty($this->branch_id)){
            foreach (explode(',',$this->branch_id) as $id){
                $pro=Branches::find($id);
                $branch[]=$pro;
            }
        }

       return $branch;
    }

    public function types(){
        return $this->hasOne('App\Models\Type','id','type_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }


}
