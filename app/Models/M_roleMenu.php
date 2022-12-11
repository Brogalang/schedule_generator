<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_roleMenu extends Model
{
    use HasFactory;
    protected $table = 'role_menu';
    protected $fillable = [
        'id','karyawanid','menuid','akses','add','update','delete','export','created_at','updated_at'
    ];
}