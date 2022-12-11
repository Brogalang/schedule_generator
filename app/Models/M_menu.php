<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_menu extends Model
{
    use HasFactory;
    protected $table = 'menu';
    protected $fillable = [
        'id','title','parent_id','status','action','created_at','updated_at'
    ];
}