<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModel extends Model
{
    use HasFactory;

    protected $table = 'afears_course';
    protected $fillable = [
        'department_id',
        'name',
        'code'
    ];
    
    public function departments() {
        return $this->belongsTo(DepartmentModel::class, 'department_id', 'id');
    }
    
  
}
