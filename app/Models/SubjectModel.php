<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    use HasFactory;

    protected $table = 'afears_subject';
    protected $fillable = [
        'course_id',
        'name',
        'code'
    ];

    public function courses() {
        return $this->belongsTo(CourseModel::class, 'course_id', 'id');
    }

}
