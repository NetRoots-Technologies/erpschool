<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\Models\Admin\BankTaxes;
use App\Models\Supplier;

class Branches extends Model
{
    use SoftDeletes;
    //
    protected $table = 'branches';
    protected $fillable = [
        'company_id',
        'name',
        'branch_code',
        'address',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $guarded = ['id'];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function allBranches()
    {
        $Branches = Branches::selectRaw('id,name')
            ->where(['status' => 1])
            ->get();
        return $Branches;
    }

    public function scopeActive()
    {
        return self::where('status', 1);
    }

    static public function branchDetailById($id)
    {
        $Branch = Branches::selectRaw('id,name')
            ->where(['status' => 1])
            ->where(['id' => $id])
            ->first();
        return $Branch;
    }
    static function branchesOpt($ID = 0)
    {
        $opt = '';
        $result = self::where('id', $ID)->get(['id', 'name']);
        foreach ($result as $row) {
            $opt .= '<option ' . (($row->id == $ID) ? 'selected' : '') . ' value=' . $row->id . '>' . $row->name . '</option>';
        }
        return $opt;
    }
    public static function BranchesByRegion($id)
    {
        $Branches = Branches::selectRaw('id,name,region_id')
            ->where(['status' => 1])
            ->where(['region_id' => $id])
            ->get();
        return $Branches;
    }
    public function branchBanksTax()
    {
        return $this->hasMany(BankTaxes::class, 'branch_id');
    }
    public static function allActiveBranches()
    {
        $Branches = Branches::selectRaw('id,name,region_id')
            ->where(['status' => 1])->get();
        return $Branches;
    }

    static public function pluckActiveOnly()
    {
        return self::where(['status' => 1])->OrderBy('name', 'asc')->pluck('name', 'id', 'region_id');
    }

    static public function pluckRegionBranches($region_id)
    {
        return self::where(['status' => 1, 'region_id' => $region_id])->OrderBy('name', 'asc')->pluck('name', 'id');
    }
    static public function pluckAllBranches()
    {
        return self::where(['status' => 1])->OrderBy('name', 'asc')->pluck('name', 'id');
    }
    static public function pluckRegionBranchesIds($region_id)
    {
        return self::where(['status' => 1, 'region_id' => $region_id])->OrderBy('name', 'asc')->pluck('id');
    }

    public function company()
    {
        return $this->belongsTo(Companies::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Departments::class);
    }

    public function suppliers()
    {
        //branch has many suppliers and supplier belongs to many branches 
        return $this->belongsToMany(Supplier::class, 'branch_suppliers', 'branch_id', 'supplier_id');
    }


    // public function suppliers()
    // {
    //     return $this->belongsToMany(Supplier::class, 'branch_suppliers', 'branch_id', 'supplier_id');
    // }



}
