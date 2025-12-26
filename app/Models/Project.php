<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $connection = 'pgsql_ppdo';
    protected $table = 'projects';

    protected $fillable = [
        'particulars',
        'year',
        'status',
        'budget_allocated',
        'obligated_budget',
        'budget_utilized',
        'utilization_rate',
    ];
}
