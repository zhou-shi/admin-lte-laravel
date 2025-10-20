@extends('adminlte::page')

@section('title', 'Tambah Prodi')

@section('content_header')
    <h1>Tambah Prodi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('prodi.store') }}" method="POST">
                @csrf
                
                @php
                $options = [];
                foreach ($jurusans as $jurusan) {
                    $options[$jurusan->id] = $jurusan->nama_jurusan;
                }
                @endphp
                <x-adminlte-select name="jurusan_id" label="Jurusan" fgroup-class="col-md-12" required>
                    <x-adminlte-options :options="$options" placeholder="Pilih Jurusan..."/>
                </x-adminlte-select>

                <x-adminlte-input name="kode_prodi" label="Kode Prodi" placeholder="Contoh: TIF" fgroup-class="col-md-12" value="{{ old('kode_prodi') }}" required/>
                <x-adminlte-input name="nama_prodi" label="Nama Prodi" placeholder="Contoh: Teknik Informatika" fgroup-class="col-md-12" value="{{ old('nama_prodi') }}" required/>
                <x-adminlte-input name="jenjang" label="Jenjang" placeholder="Contoh: S1" fgroup-class="col-md-12" value="{{ old('jenjang') }}" required/>
                
                <x-adminlte-button type="submit" label="Simpan" theme="primary" icon="fas fa-save"/>
                <a href="{{ route('prodi.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop