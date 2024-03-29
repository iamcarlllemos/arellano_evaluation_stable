<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FacultyModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'afears_faculty';
    protected $fillable = [
        'department_id',
        'employee_number',
        'firstname',
        'lastname',
        'middlename',
        'gender',
        'image',
        'email',
    ];

    protected $hidden = [
        'password',
    ];

    public function departments() {
        return $this->belongsTo(DepartmentModel::class, 'department_id', 'id');
    }

    public function templates() {
        return$this->hasMany(FacultyTemplateModel::class, 'faculty_id', 'id');
    }

}
