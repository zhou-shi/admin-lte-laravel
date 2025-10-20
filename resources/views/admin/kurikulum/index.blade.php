@extends('adminlte::page')

@section('title', 'Data Kurikulum')

@section('content_header')
    <h1>Data Kurikulum</h1>
@stop

@section('content')
    <a href="{{ route('kurikulum.create') }}" class="btn btn-primary mb-3">Tambah Kurikulum</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Tahun</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kurikulums as $kurikulum)
            <tr>
                <td>{{ $kurikulum->kode }}</td>
                <td>{{ $kurikulum->nama }}</td>
                <td>{{ $kurikulum->tahun }}</td>
                <td>{{ $kurikulum->keterangan }}</td>
                <td>
                    <a href="{{ route('kurikulum.show', $kurikulum->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    <a href="{{ route('kurikulum.edit', $kurikulum->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('kurikulum.destroy', $kurikulum->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
    {{-- <link rel="stylesheet" href=""> --}}
@stop

@section('js')
    {{-- <script> console.log('Hi!'); </script> --}}
@stop
