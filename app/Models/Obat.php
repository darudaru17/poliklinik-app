<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';  // ← pastikan baris ini ada!
    protected $fillable = ['nama_obat', 'kemasan', 'harga'];

    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }
}