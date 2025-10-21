<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
    protected $table = 'public_holiday';

    protected $fillable = [
        'year',
        'holiday_date',
        'holiday_name',
        'status',
        'created_by',
        'updated_by'
        
    ];

    protected function casts(): array
    {
        return [
            'holiday_date' => 'datetime'
        ];
    }
}


