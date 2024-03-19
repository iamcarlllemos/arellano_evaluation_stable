<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacultyTemplateModel extends Model
{
    use HasFactory;

    protected $table = 'afears_faculty_template';
    protected $fillable = [
        'faculty_id',
        'template_id',
    ];

    public function curriculum_template() {
        return $this->hasMany(CurriculumTemplateModel::class, 'id', 'template_id');
    }

    public function faculty() {
        return $this->belongsTo(FacultyModel::class, 'faculty_id', 'id');
    }

}
