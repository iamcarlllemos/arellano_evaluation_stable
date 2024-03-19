<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYearModel extends Model
{
    use HasFactory;

    protected $table = 'afears_school_year';
    protected $fillable = [
        'name',
        'start_year',
        'end_year',
        'semester',
        'status'
    ];

}
