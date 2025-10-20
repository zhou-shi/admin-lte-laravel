@extends('adminlte::page')

@section('title', 'Data Prodi')

@section('content_header')
    <h1>Data Program Studi</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <a href="{{ route('prodi.create') }}" class="btn btn-primary">Tambah Prodi</a>
            </div>
            @php
            $heads = [
                'ID',
                'Kode Prodi',
                'Nama Prodi',
                'Jenjang',
                'Jurusan',
                ['label' => 'Aksi', 'no-export' => true, 'width' => 16],
            ];
            @endphp
            <x-adminlte-datatable id="table-prodi" :heads="$heads" bordered compressed hoverable with-buttons>
                @foreach($prodis as $prodi)
                    <tr>
                        <td>{{ $prodi->id }}</td>
                        <td>{{ $prodi->kode_prodi }}</td>
                        <td>{{ $prodi->nama_prodi }}</td>
                        <td>{{ $prodi->jenjang }}</td>
                        <td>{{ $prodi->jurusan->nama_jurusan }}</td> <td>
                            <form action="{{ route('prodi.destroy', $prodi->id) }}" method="POST">
                                <a href="{{ route('prodi.edit', $prodi->id) }}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@stop