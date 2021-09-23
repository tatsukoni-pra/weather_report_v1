<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherReportLog extends Model
{
    use HasFactory;

    protected $fillable = ['coordinates', 'data'];
}
