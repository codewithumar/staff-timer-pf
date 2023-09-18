<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeIp extends Model
{
    use HasFactory;
    protected $table = "office_ip";
    protected $fillable = [
        'ip',
        'name'
    ];
}
