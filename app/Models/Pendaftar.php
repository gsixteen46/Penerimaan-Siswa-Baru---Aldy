<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;//untuk membuat data dummy saat testing

    protected $table = 'pendaftars'; //menetapkan sesuai dengan nama tabel di database
    protected $fillable = [ //ini menandakan kolom boleh diisi
        'nama',
        'email',
        'no_hp',
        'tanggal_lahir',
        'alamat',
        'asal_sekolah',
        'jenis_kelamin',
        'agama',
        'jurusan_id',
        'berkas',
        'status'
    ];

    public function jurusan()//function untuk relasi
    {
        return $this->belongsTo(Jurusan::class);//menandakan setiap pendaftar memiliki satu jurusan
    }
}
