<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class QuestionnaireModel extends Model
{
    use HasFactory;
    use Sluggable;

    protected $table = 'afears_questionnaire';
    protected $fillable = [
        'school_year_id',
        'name',
        'slug'
    ];

    public function school_year() {
        return $this->hasOne(SchoolYearModel::class, 'id', 'school_year_id');
    }

    public function questionnaire_item() {
        return $this->hasMany(QuestionnaireItemModel::class, 'questionnaire_id', 'id');
    }
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }


}
