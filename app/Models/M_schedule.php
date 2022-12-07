<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_schedule extends Model
{
    use HasFactory;
    protected $table = 'schedule';
    protected $fillable = [
        'id','bulan_scheduler','level_scheduler','divisi_scheduler','created_at','updated_at'
    ];
}
