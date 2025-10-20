@extends('adminlte::page')

@section('title', 'Edit Jurusan')

@section('content_header')
    <h1>Edit Jurusan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('jurusan.update', $jurusan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <x-adminlte-input name="kode_jurusan" label="Kode Jurusan" placeholder="Contoh: TEK" fgroup-class="col-md-12" value="{{ old('kode_jurusan', $jurusan->kode_jurusan) }}" required/>
                <x-adminlte-input name="nama_jurusan" label="Nama Jurusan" placeholder="Contoh: Fakultas Teknik" fgroup-class="col-md-12" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" required/>
                
                <x-adminlte-button type="submit" label="Update" theme="primary" icon="fas fa-save"/>
                <a href="{{ route('jurusan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop