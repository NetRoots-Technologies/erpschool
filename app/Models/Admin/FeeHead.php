<?php

namespace App\Models\Admin;

use App\Models\Group;
use App\Models\Academic\AcademicClass;
use App\Models\Student\AcademicSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeHead extends Model
{
    use HasFactory;

    protected $table = 'fee_heads';

    protected $fillable = ['session_id', 'company_id', 'branch_id', 'class_id', 'account_head_id', 'fee_section_id', 'fee_head', 'details', 'dividable', 'parent_type_id'];


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function AcademicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function AcademicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }
    public function AccountHead()
    {
        return $this->belongsTo(AccountHead::class, 'account_head_id');
    }

    public function FeeSection()
    {
        return $this->belongsTo(FeeSection::class, 'fee_section_id');
    }

    public function feeStructureVal()
    {
        return $this->hasOne(FeeStructureValue::class, 'fee_head_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'account_type_id', 'id')->where('parent_type', self::class);
    }
}
