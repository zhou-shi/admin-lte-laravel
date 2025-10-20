@extends('adminlte::page')

@section('title', 'Edit Prodi')

@section('content_header')
    <h1>Edit Prodi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('prodi.update', $prodi->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                @php
                $options = [];
                foreach ($jurusans as $jurusan) {
                    $options[$jurusan->id] = $jurusan->nama_jurusan;
                }
                @endphp
                <x-adminlte-select name="jurusan_id" label="Jurusan" fgroup-class="col-md-12" required>
                    <x-adminlte-options :options="$options" :selected="[old('jurusan_id', $prodi->jurusan_id)]" placeholder="Pilih Jurusan..."/>
                </x-adminlte-select>

                <x-adminlte-input name="kode_prodi" label="Kode Prodi" placeholder="Contoh: TIF" fgroup-class="col-md-12" value="{{ old('kode_prodi', $prodi->kode_prodi) }}" required/>
                <x-adminlte-input name="nama_prodi" label="Nama Prodi" placeholder="Contoh: Teknik Informatika" fgroup-class="col-md-12" value="{{ old('nama_prodi', $prodi->nama_prodi) }}" required/>
                <x-adminlte-input name="jenjang" label="Jenjang" placeholder="Contoh: S1" fgroup-class="col-md-12" value="{{ old('jenjang', $prodi->jenjang) }}" required/>
                
                <x-adminlte-button type="submit" label="Update" theme="primary" icon="fas fa-save"/>
                <a href="{{ route('prodi.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop