<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_divisi extends Model
{
    use HasFactory;
    protected $table = 'divisi';
    protected $fillable = [
        'id','kode_divisi','nama_divisi','created_at','updated_at'
    ];
}
