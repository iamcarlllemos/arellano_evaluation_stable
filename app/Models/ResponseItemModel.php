<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseItemModel extends Model
{
    use HasFactory;

    protected $table = 'afears_response_item';

    protected $fillable = [
        'response_id',
        'questionaire_id',
        'response_rating',
    ];

    public $timestamps = false;

}
