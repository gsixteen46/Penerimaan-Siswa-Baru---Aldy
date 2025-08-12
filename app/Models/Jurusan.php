<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    //
    protected $fillable = ['nama_jurusan', 'deskripsi'];

    public function pendaftars()
    {
        return $this->hasMany(Pendaftar::class);
    }
}
