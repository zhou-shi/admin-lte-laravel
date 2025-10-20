@extends('adminlte::page')

@section('title', 'Tambah Mahasiswa')

@section('content_header')
    <h1>Tambah Mahasiswa</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('mahasiswa.store') }}" method="POST">
                @csrf
                
                @php
                $prodiOptions = [];
                foreach ($prodis as $prodi) {
                    $prodiOptions[$prodi->id] = $prodi->nama_prodi . ' (' . $prodi->jenjang . ')';
                }
                $jkOptions = ['L' => 'Laki-laki', 'P' => 'Perempuan'];
                @endphp

                <x-adminlte-input name="nim" label="NIM" placeholder="Nomor Induk Mahasiswa" fgroup-class="col-md-12" value="{{ old('nim') }}" required/>
                <x-adminlte-input name="nama_mahasiswa" label="Nama Mahasiswa" placeholder="Nama Lengkap" fgroup-class="col-md-12" value="{{ old('nama_mahasiswa') }}" required/>
                
                <x-adminlte-select name="prodi_id" label="Program Studi" fgroup-class="col-md-12" required>
                    <x-adminlte-options :options="$prodiOptions" placeholder="Pilih Prodi..."/>
                </x-adminlte-select>

                <x-adminlte-input name="angkatan" label="Angkatan (Tahun)" type="number" placeholder="Contoh: 2023" fgroup-class="col-md-12" value="{{ old('angkatan') }}" required/>
                
                <x-adminlte-select name="jenis_kelamin" label="Jenis Kelamin" fgroup-class="col-md-12" required>
                    <x-adminlte-options :options="$jkOptions" placeholder="Pilih Jenis Kelamin..."/>
                </x-adminlte-select>
                
                <x-adminlte-textarea name="alamat" label="Alamat" placeholder="Alamat lengkap" fgroup-class="col-md-12">{{ old('alamat') }}</x-adminlte-textarea>

                <x-adminlte-button type="submit" label="Simpan" theme="primary" icon="fas fa-save"/>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop