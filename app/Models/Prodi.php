<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'jurusan_id',
        'kode_prodi',
        'nama_prodi',
        'jenjang',
    ];

    // Relasi ke Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    // Relasi ke Mahasiswa (nanti)
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }
}