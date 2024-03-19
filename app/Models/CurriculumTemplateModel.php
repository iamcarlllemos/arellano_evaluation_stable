<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumTemplateModel extends Model
{
    use HasFactory;

    protected $table = 'afears_curriculum_template';
    protected $fillable = [
        'department_id',
        'course_id',
        'subject_id',
        'subject_sem',
        'year_level',
    ];

    public function departments() {
        return $this->belongsTo(DepartmentModel::class, 'department_id');
    }

    public function courses() {
        return $this->belongsTo(CourseModel::class, 'course_id');
    }

    public function subjects() {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }
    

}
