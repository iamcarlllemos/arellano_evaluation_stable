<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StudentModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'afears_student';
    protected $fillable = [
        'course_id',
        'student_number',
        'firstname',
        'lastname',
        'middlename',
        'gender',
        'birthday',
        'year_level',
        'image',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function courses() {
        return $this->belongsTo(CourseModel::class, 'course_id', 'id');
    }

    public function template() {
        return $this->hasMany(CurriculumTemplateModel::class, 'id');
    }

}
