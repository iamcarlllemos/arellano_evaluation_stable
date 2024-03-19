<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireItemModel extends Model
{
    use HasFactory;

    protected $table = 'afears_questionnaire_item';
    protected $fillable = [
        'questionnaire_id',
        'criteria_id',
        'item'
    ];

    public function criteria() {
        return $this->hasOne(CriteriaModel::class, 'id', 'criteria_id');
    }

}
