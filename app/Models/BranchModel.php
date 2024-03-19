<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchModel extends Model
{
    use HasFactory;
    
    protected $table = 'afears_branch';
    protected $fillable = [
        'name',
        'image',
        'slug'
    ];

    public function departments() {
        return $this->hasMany(DepartmentModel::class, 'branch_id', 'id');
    }

    public function courses() {
        return $this->hasMany(CourseModel::class, 'department_id', 'id');
    }

}
