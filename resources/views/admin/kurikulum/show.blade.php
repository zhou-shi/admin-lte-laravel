@extends('adminlte::page')

@section('title', 'Show Kurikulum')

@section('content_header')
    <h1>Detail Kurikulum</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Kode: {{ $kurikulum->kode }}</h5>
            <p class="card-text"><strong>Nama:</strong> {{ $kurikulum->nama }}</p>
            <p class="card-text"><strong>Tahun:</strong> {{ $kurikulum->tahun }}</p>
            <p class="card-text"><strong>Keterangan:</strong> {{ $kurikulum->keterangan }}</p>
            <a href="{{ route('kurikulum.index') }}" class="btn btn-success">Kembali</a>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href=""> --}}
@stop

@section('js')
    {{-- <script> console.log('Hi!'); </script> --}}
@stop