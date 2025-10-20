<?php
namespace App\Http\Controllers\Admin; // Pastikan namespace benar

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index()
    {
        $prodis = Prodi::with('jurusan')->get(); // Eager load relasi jurusan
        return view('admin.prodi.index', compact('prodis'));
    }

    public function create()
    {
        $jurusans = Jurusan::all();
        return view('admin.prodi.create', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'kode_prodi' => 'required|string|unique:prodis,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:10',
        ]);

        Prodi::create($request->all());

        return redirect()->route('prodi.index')
            ->with('success', 'Prodi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        $jurusans = Jurusan::all();
        return view('admin.prodi.edit', compact('prodi', 'jurusans'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusans,id',
            'kode_prodi' => 'required|string|unique:prodis,kode_prodi,' . $prodi->id,
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:10',
        ]);

        $prodi->update($request->all());

        return redirect()->route('prodi.index')
            ->with('success', 'Prodi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();

        return redirect()->route('prodi.index')
            ->with('success', 'Prodi berhasil dihapus.');
    }
}