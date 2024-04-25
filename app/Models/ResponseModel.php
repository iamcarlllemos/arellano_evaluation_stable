<?php

namespace App\Models;

use App\Livewire\Admin\CurriculumTemplate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseModel extends Model
{
    use HasFactory;

    protected $table = 'afears_response';

    protected $fillable = [
        'user_id',
        'evaluation_id',
        'template_id',
        'faculty_id',
        'semester',
        'start_time',
        'end_time',
        'comment',
    ];

    public function items() {
        return $this->hasMany(ResponseItemModel::class, 'response_id', 'id');
    }

    public function students() {
        return $this->belongsTo(StudentModel:: class, 'user_id');
    }

    public function template() {
        return $this->belongsTo(CurriculumTemplateModel::class, 'template_id');
    }

}
