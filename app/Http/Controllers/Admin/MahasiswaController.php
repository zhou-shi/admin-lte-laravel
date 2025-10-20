<?php
namespace App\Http\Controllers\Admin; // Pastikan namespace benar

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('prodi')
                                ->take(5)
                                ->get(); // Eager load relasi prodi

        $mahasiswa_count = Mahasiswa::count();

        return view('admin.mahasiswa.index', compact('mahasiswas', 'mahasiswa_count'));
    }

    public function create()
    {
        $prodis = Prodi::all();
        return view('admin.mahasiswa.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'nama_mahasiswa' => 'required|string|max:255',
            'angkatan' => 'required|numeric|digits:4',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
        ]);

        Mahasiswa::create($request->all());

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $prodis = Prodi::all();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'prodis'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'prodi_id' => 'required|exists:prodis,id',
            'nim' => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama_mahasiswa' => 'required|string|max:255',
            'angkatan' => 'required|numeric|digits:4',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
        ]);

        $mahasiswa->update($request->all());

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }

    /**
     * Handle AJAX request to load more jurusans.
     */
    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0); // Ambil offset (data yang sudah ditampilkan)
        $limit = 5; // Ambil 5 data baru

        $mahasiswas = Mahasiswa::with('prodi')->skip($offset)->take($limit)->get();

        $total_count = Mahasiswa::count();
        $new_offset = $offset + $mahasiswas->count();
        $remaining = $total_count - $new_offset;

        return response()->json([
            'mahasiswas' => $mahasiswas,    // Data baru
            'new_offset' => $new_offset, // Offset baru untuk request selanjutnya
            'remaining' => $remaining   // Sisa data
        ]);
    }
}