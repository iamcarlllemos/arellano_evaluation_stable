<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    use HasFactory;

    protected $table = 'afears_department';
    protected $fillable = [
        'branch_id',
        'name',
        'date_added',
        'date_updated'
    ];


    public function courses() {
        return $this->belongsTo(CourseModel::class, 'course_id', 'id');
    }
    public function branches() {
        return $this->belongsTo(BranchModel::class, 'branch_id', 'id');
    }

}
