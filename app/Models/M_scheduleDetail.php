<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_scheduleDetail extends Model
{
    use HasFactory;
    protected $table = 'schedule_detail';
    protected $fillable = [
        'id','schedule_id','karyawanid','tanggal','shift','tanda','created_at','updated_at'
    ];
}
