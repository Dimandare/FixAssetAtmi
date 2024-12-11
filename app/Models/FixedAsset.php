<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedAsset extends Model
{
    use HasFactory;
   
     protected $table = 'fixed_assets'; // Sesuaikan dengan nama tabel di database Anda
    
    protected $primaryKey = 'id_fa';
    public $incrementing = false;
    protected $fillable = [
        'id_fa', 'id_institusi', 'id_divisi', 'id_tipe', 'id_kelompok', 'id_jenis', 'id_lokasi',
        'id_ruang', 'no_permintaan', 'tahun_diterima', 'kode_fa', 'nama_barang', 'foto_barang', 'des_barang', 'status_transaksi',
        'status_barang', 'id_user', 'status_fa', 'jumlah_unit'
    ];

    public function institusi(): BelongsTo
    {
        return $this->belongsTo(Institusi::class, 'id_institusi');
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'id_divisi');
    }

    public function tipe(): BelongsTo
    {
        return $this->belongsTo(Tipe::class, 'id_tipe');
    }

    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(Jenis::class, 'id_jenis');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi');
    }

    public function ruang(): BelongsTo
    {
        return $this->belongsTo(Ruang::class, 'id_ruang');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
