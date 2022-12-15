<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_JamKerja extends Model
{
    use HasFactory;
    protected $table = 'jamkerja';
    protected $fillable = [
        'id','pagi','siang','malam','deleted_at','created_at','updated_at'
    ];
}
