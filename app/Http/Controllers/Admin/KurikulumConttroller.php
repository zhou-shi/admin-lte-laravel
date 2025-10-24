<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use Illuminate\Http\Request;

class KurikulumConttroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $limit = 5; // Tetapkan limit data awal
        $kurikulums = Kurikulum::take($limit)->get();
        $total_kurikulum = Kurikulum::count(); // Hitung total

        return view('admin.kurikulum.index', compact('kurikulums', 'total_kurikulum')); // Kirim total ke view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kurikulum.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kode_kurikulum' => 'required|string|max:10|unique:kurikulums,kode_kurikulum',
            'nama_kurikulum' => 'required|string|max:255',
            'tahun' => 'required|integer|digits:4',
        ]);

        // Buat kurikulum baru
        Kurikulum::create($validatedData);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kurikulum $kurikulum)
    {
        return view('admin.kurikulum.show', compact('kurikulum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kurikulum $kurikulum)
    {
        return view('admin.kurikulum.edit', compact('kurikulum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kurikulum $kurikulum)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kode_kurikulum' => 'required|string|max:10|unique:kurikulums,kode_kurikulum,' . $kurikulum->id,
            'nama_kurikulum' => 'required|string|max:255',
            'tahun' => 'required|integer|digits:4',
        ]);

        // Update data kurikulum
        $kurikulum->update($validatedData);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kurikulum $kurikulum)
    {
        // Hapus data kurikulum
        $kurikulum->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum berhasil dihapus.');
    }

    /**
     * Handle AJAX request to load more kurikulums.
     */
    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = 5;

        $kurikulums = Kurikulum::skip($offset)->take($limit)->get();

        $total_count = Kurikulum::count();
        $new_offset = $offset + $kurikulums->count();
        $remaining = $total_count - $new_offset;

        return response()->json([
            'kurikulums' => $kurikulums, // Kirim data kurikulum
            'new_offset' => $new_offset,
            'remaining' => $remaining
        ]);
    }
}
