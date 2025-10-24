<?php

// app/Http/Controllers/Admin/JurusanController.php
namespace App\Http\Controllers\Admin; // Pastikan namespace benar

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        // Ambil 8 data pertama saja
        $jurusans = Jurusan::take(5)->get();

        // Ambil total data untuk logika tombol
        $total_jurusan = Jurusan::count();

        return view('admin.jurusan.index', compact('jurusans', 'total_jurusan'));
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|unique:jurusans,kode_jurusan',
            'nama_jurusan' => 'required|string|max:255',
        ]);

        Jurusan::create($request->all());

        return redirect()->route('jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function show(Jurusan $jurusan)
    {
        // Kita bisa gunakan view 'edit' atau buat view 'show' khusus
        // Untuk saat ini, kita redirect ke edit
        return redirect()->route('jurusan.edit', $jurusan);
    }

    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|unique:jurusans,kode_jurusan,' . $jurusan->id,
            'nama_jurusan' => 'required|string|max:255',
        ]);

        $jurusan->update($request->all());

        return redirect()->route('jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        // Tambahkan pengecekan relasi jika perlu
        // if ($jurusan->prodis()->count() > 0) {
        //     return back()->with('error', 'Jurusan tidak dapat dihapus karena memiliki prodi terkait.');
        // }

        $jurusan->delete();

        return redirect()->route('jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }

    /**
     * Handle AJAX request to load more jurusans.
     */
    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0); // Ambil offset (data yang sudah ditampilkan)
        $limit = 5; // Ambil 8 data baru

        $jurusans = Jurusan::skip($offset)->take($limit)->get();

        $total_count = Jurusan::count();
        $new_offset = $offset + $jurusans->count();
        $remaining = $total_count - $new_offset;

        return response()->json([
            'jurusans' => $jurusans,    // Data baru
            'new_offset' => $new_offset, // Offset baru untuk request selanjutnya
            'remaining' => $remaining   // Sisa data
        ]);
    }
}