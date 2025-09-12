<?php

namespace App\Models\Exam;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubComponent extends Model
{
    use HasFactory;

    protected $table = 'sub_components';

    protected $fillable = ['component_id', 'test_type_id', 'comp_name', 'comp_number', 'user_id'];

    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id');
    }
    public function componentData()
    {
        return $this->belongsTo(ComponentData::class, 'component_id');
    }


    public function test_type()
    {
        return $this->belongsTo(TestType::class, 'test_type_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
