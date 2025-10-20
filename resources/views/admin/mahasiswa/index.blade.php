@extends('adminlte::page')

@section('title', 'Data Mahasiswa')

@section('content_header')
    <h1>Data Mahasiswa</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">Tambah Mahasiswa</a>
            </div>
            @php
            $heads = [
                'ID',
                'NIM',
                'Nama Mahasiswa',
                'L/P',
                'Angkatan',
                'Program Studi',
                ['label' => 'Aksi', 'no-export' => true, 'width' => 16],
            ];
            @endphp
            <x-adminlte-datatable id="table-mahasiswa" :heads="$heads" bordered compressed hoverable with-buttons>
                @foreach($mahasiswas as $mahasiswa)
                    <tr>
                        <td>{{ $mahasiswa->id }}</td>
                        <td>{{ $mahasiswa->nim }}</td>
                        <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                        <td>{{ $mahasiswa->jenis_kelamin }}</td>
                        <td>{{ $mahasiswa->angkatan }}</td>
                        <td>{{ $mahasiswa->prodi->nama_prodi }}</td> <td>
                            <form action="{{ route('mahasiswa.destroy', $mahasiswa->id) }}" method="POST">
                                <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
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

            {{-- 👇 TAMBAHKAN BLOK TOMBOL INI 👇 --}}
            <div class="mt-3 text-center">
                @php
                    $current_count = $mahasiswas->count();
                @endphp

                <button id="load-more-btn" class="btn btn-primary" 
                        data-offset="{{ $current_count }}" 
                        style="{{ ($current_count < $mahasiswa_count) ? '' : 'display:none;' }}">
                    <i class="fas fa-plus mr-1"></i> Tampilkan Lebih
                </button>

                <button id="show-less-btn" class="btn btn-secondary" style="display:none;">
                    <i class="fas fa-minus mr-1"></i> Tampilkan Sedikit
                </button>
            </div>
            {{-- 👆 BATAS BLOK TOMBOL 👆 --}}
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // 1. Dapatkan instance dari DataTable
    // Ganti 'table-jurusan' jika ID tabel Anda berbeda
    let table = $('#table-mahasiswa').DataTable();

    // 2. Logika Tombol "Tampilkan Lebih"
    $('#load-more-btn').on('click', function() {
        let btn = $(this);
        let offset = btn.data('offset');

        // Nonaktifkan tombol saat memuat
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memuat...');

        $.ajax({
            url: "{{ route('mahasiswa.loadMore') }}",
            type: 'GET',
            data: {
                offset: offset
            },
            success: function(response) {
                let firstNewNode = null;

                response.mahasiswas.forEach(function(mahasiswa) {
                    // 3. Buat HTML untuk kolom Aksi (PENTING!)
                    // Kita harus membuat URL dan token CSRF secara manual di sini
                    let editUrl = '{{ url("admin/mahasiswa") }}/' + mahasiswa.id + '/edit';
                    let deleteUrl = '{{ url("admin/mahasiswa") }}/' + mahasiswa.id;
                    let csrfToken = '{{ csrf_token() }}';

                    let actionButtons = `
                        <form action="${deleteUrl}" method="POST">
                            <a href="${editUrl}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </a>
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        </form>
                    `;

                    // 4. Tambahkan baris baru ke DataTable
                    let rowNode = table.row.add([
                        mahasiswa.id,
                        mahasiswa.kode_mahasiswa,
                        mahasiswa.nama_mahasiswa,
                        actionButtons
                    ]).draw(false).node(); // draw(false) agar tidak reset ke halaman 1

                    // Tambahkan class align-middle ke setiap sel
                    $(rowNode).find('td').addClass('align-middle');

                    // Simpan baris pertama yang baru ditambahkan
                    if (!firstNewNode) {
                        firstNewNode = rowNode;
                    }
                });

                // 5. Scroll ke data baru
                if (firstNewNode) {
                    $('html, body').animate({
                        scrollTop: $(firstNewNode).offset().top - 60 // 60px untuk header
                    }, 500);
                }

                // 6. Update status tombol
                btn.data('offset', response.new_offset); // Update offset baru
                btn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i> Tampilkan Lebih');

                if (response.remaining <= 0) {
                    btn.hide(); // Sembunyikan jika data sudah habis
                }

                $('#show-less-btn').show(); // Tampilkan tombol "Tampilkan Sedikit"
            },
            error: function() {
                alert('Gagal memuat data.');
                btn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i> Tampilkan Lebih');
            }
        });
    });

    // 7. Logika Tombol "Tampilkan Sedikit"
    $('#show-less-btn').on('click', function() {
        let rowCount = table.rows().count();
        let removeCount = 0;

        if (rowCount > 5) {
            // Tentukan berapa banyak yang harus dihapus, maksimal 5
            removeCount = Math.min(5, rowCount - 5); 
        }

        if (removeCount > 0) {
            // Hapus 5 baris terakhir dari tabel
            table.rows().slice(rowCount - removeCount, rowCount).remove().draw();

            // Update offset tombol "load more"
            let newOffset = table.rows().count();
            $('#load-more-btn').data('offset', newOffset).show();
        }

        // Sembunyikan tombol "show less" jika data sisa 5 atau kurang
        if (table.rows().count() <= 5) {
            $(this).hide();
        }
    });
});
</script>
@stop