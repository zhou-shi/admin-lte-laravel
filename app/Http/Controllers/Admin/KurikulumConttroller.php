<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kurikulum;

class KurikulumConttroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kurikulums= Kurikulum::all();
        return view('admin.kurikulum.index', compact('kurikulums'));
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
        $validated = $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'tahun' => 'required|numeric|digits:4',
        ]);

        Kurikulum::create($validated);
        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kurikulum = Kurikulum::findOrFail($id);
        return view('admin.kurikulum.show', compact('kurikulum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kurikulum = Kurikulum::findOrFail($id);
        return view('admin.kurikulum.edit', compact('kurikulum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'tahun' => 'required|numeric|digits:4',
        ]);

        $kurikulum = Kurikulum::findOrFail($id);
        $kurikulum->update($validated);
        
        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kurikulum = Kurikulum::findOrFail($id);
        $kurikulum->delete();
        
        return redirect()->route('kurikulum.index')->with('success', 'Kurikulum deleted successfully.');
    }
}
