<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::orderBy('nama_obat')->get();
        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan' => 'required|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        Obat::create($request->only(['nama_obat', 'kemasan', 'harga', 'stok']));

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat Berhasil dibuat')
            ->with('type', 'success');
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'kemasan' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update($request->only(['nama_obat', 'kemasan', 'harga', 'stok']));

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil di edit')
            ->with('type', 'success');
    }

    public function destroy(string $id)
    {
        Obat::findOrFail($id)->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat berhasil di Hapus')
            ->with('type', 'success');
    }

    // === Manajemen Stok Manual ===
    public function tambahStok(Request $request, string $id)
    {
        $request->validate(['jumlah' => 'required|integer|min:1']);

        $obat = Obat::findOrFail($id);
        $obat->increment('stok', $request->jumlah);

        return redirect()->route('obat.index')
            ->with('message', "Stok {$obat->nama_obat} ditambah {$request->jumlah}")
            ->with('type', 'success');
    }

    public function kurangiStok(Request $request, string $id)
    {
        $request->validate(['jumlah' => 'required|integer|min:1']);

        $obat = Obat::findOrFail($id);

        if ($request->jumlah > $obat->stok) {
            return redirect()->route('obat.index')
                ->with('message', "Gagal: stok {$obat->nama_obat} tidak cukup (tersisa {$obat->stok})")
                ->with('type', 'error');
        }

        $obat->decrement('stok', $request->jumlah);

        return redirect()->route('obat.index')
            ->with('message', "Stok {$obat->nama_obat} dikurangi {$request->jumlah}")
            ->with('type', 'success');
    }
}