<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Ditambahkan agar DB::transaction berjalan lancar

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        // obat_json sekarang berisi array of {id, jumlah}
        $items = json_decode($request->obat_json, true);

        // Validasi stok SEBELUM menyimpan apapun
        foreach ($items as $item) {
            $obat = Obat::find($item['id']);
            if (!$obat) {
                return back()->withInput()->with('error', 'Obat tidak ditemukan.');
            }
            if ($obat->stok < $item['jumlah']) {
                return back()->withInput()->with(
                    'error',
                    "Stok {$obat->nama_obat} tidak cukup. Tersisa {$obat->stok}, diminta {$item['jumlah']}."
                );
            }
        }

        DB::transaction(function () use ($request, $items) {
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa' => now(),
                'catatan' => $request->catatan,
                'biaya_periksa' => $request->biaya_periksa + 150000,
            ]);

            foreach ($items as $item) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat' => $item['id'],
                    'jumlah' => $item['jumlah'],
                ]);

                // kurangi stok otomatis
                Obat::where('id', $item['id'])->decrement('stok', $item['jumlah']);
            }
        });

        return redirect()->route('periksa-pasien.index')->with('success', 'Data periksa berhasil disimpan.');
    }
}