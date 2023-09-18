<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkersRecord extends Model
{
    use HasFactory;
    protected $table = "workersrecord";
    protected $fillable = ['userid', 'total_hours_in_office', 'total_out_of_office', 'attendance', 'totaltime'];
}
