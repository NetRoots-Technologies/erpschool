<?php

namespace App\Models\Exam;

use App\Models\Academic\AcademicClass;
use App\Models\Admin\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    use HasFactory;

    protected $table = "skills";

    protected $fillable = [
        "name",
        "status",
        "logs",
        "class_id",
        "course_id",
        "component_id"
    ];

    /**
     * Relationships
     */

    public function class()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id');
    }
}
