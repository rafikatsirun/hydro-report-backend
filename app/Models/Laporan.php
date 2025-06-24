<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
    'nama_pelapor',
    'nomor_pelapor',
    'alamat_pelapor',
    'tanggal_pelaporan',
    'deskripsi',
    'latitude',
    'longitude',
    'status_laporan',
];

}
