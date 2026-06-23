<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $fillable = ['nama_obat', 'kemasan', 'harga', 'stok'];

    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }

    // batas "stok menipis" — silakan ubah sesuai kebutuhan
    const BATAS_MENIPIS = 10;

    public function getStatusStokAttribute()
    {
        if ($this->stok <= 0) return 'habis';
        if ($this->stok <= self::BATAS_MENIPIS) return 'menipis';
        return 'aman';
    }
}