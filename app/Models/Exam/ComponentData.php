<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentData extends Model
{
    use HasFactory;

    protected $table = 'components_data';

    protected $fillable = ['component_id', 'weightage', 'type_id','total_marks'];


    public function componentData()
    {
        return $this->belongsTo(ComponentData::class, 'component_id');
    }

    public function test_type()
    {
        return $this->belongsTo(TestType::class, 'type_id');
    }
}
