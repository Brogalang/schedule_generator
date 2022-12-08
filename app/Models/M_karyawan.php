<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_karyawan extends Model
{
    use HasFactory;
    protected $table = 'karyawan';
    protected $fillable = [
        'id','nama_karyawan','level_karyawan','divisi_karyawan','jabatan','alamat','jk','tanggallahir','tmptlahir','tglmasuk','created_at','updated_at'
    ];
}
