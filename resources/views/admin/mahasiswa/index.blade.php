@extends('adminlte::page')

@section('title', 'Data Mahasiswa')

@section('content_header')
    <h1>Data Mahasiswa</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Tombol Tambah --}}
            <div class="mb-3">
                <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">Tambah Mahasiswa</a>
            </div>

            {{-- Definisikan Kolom Tabel --}}
            @php
            $heads = [
                'ID',
                'NIM',
                'Nama Mahasiswa',
                'L/P',
                'Angkatan',
                'Program Studi',
                ['label' => 'Aksi', 'no-export' => true, 'width' => 'auto'], 
            ];
            @endphp

            {{-- Komponen DataTable --}}
            <x-adminlte-datatable id="table-mahasiswa" :heads="$heads" theme="light" striped hoverable bordered compressed with-buttons>
                {{-- Data Awal --}}
                @foreach($mahasiswas as $mahasiswa)
                    <tr>
                        <td class="align-middle">{{ $mahasiswa->id }}</td>
                        <td class="align-middle">{{ $mahasiswa->nim }}</td>
                        <td class="align-middle">{{ $mahasiswa->nama_mahasiswa }}</td>
                        <td class="align-middle">{{ $mahasiswa->jenis_kelamin }}</td>
                        <td class="align-middle">{{ $mahasiswa->angkatan }}</td>
                        <td class="align-middle">{{ $mahasiswa->prodi ? $mahasiswa->prodi->nama_prodi : 'N/A' }}</td> 
                        <td class="align-middle text-nowrap"> 
                            <form action="{{ route('mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="d-inline">
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

            {{-- Tombol Load More & Show Less --}}
            <div class="mt-3 text-center">
                @php
                    $current_count = $mahasiswas->count();
                    $limit = 5; // Pastikan ini sama dengan limit di controller
                @endphp
                
                <button id="load-more-btn" class="btn btn-primary" 
                        data-offset="{{ $current_count }}" 
                        style="{{ ($current_count < $mahasiswa_count) ? '' : 'display:none;' }}">
                    <i class="fas fa-plus mr-1"></i> Tampilkan Lebih
                </button>
                
                <button id="show-less-btn" class="btn btn-secondary" 
                        style="{{ $current_count > $limit ? '' : 'display:none;' }}"> 
                    <i class="fas fa-minus mr-1"></i> Tampilkan Sedikit
                </button>
            </div>
            
        </div>
    </div>
@stop

{{-- ================================================================================== --}}
{{-- BAGIAN JAVASCRIPT --}}
{{-- ================================================================================== --}}
@section('js')
<script>
$(document).ready(function() {
    
    // Simpan referensi ke tombol
    const loadMoreBtn = $('#load-more-btn');
    const showLessBtn = $('#show-less-btn');
    const limit = 5; // Harus sama dengan limit di Controller
    const tableId = '#table-mahasiswa'; // Simpan ID tabel

    // Fungsi helper untuk mendapatkan instance DataTable yang sudah ada
    function getTableInstance() {
        try {
            if ($.fn.dataTable.isDataTable(tableId)) {
                return $(tableId).DataTable(); 
            } else {
                 console.warn("DataTable belum diinisialisasi.");
                 return null; 
            }
        } catch(e) {
            console.error("Error saat mengakses DataTable:", e);
            return null; 
        }
    }

    // --- LOGIKA TOMBOL LOAD MORE ---
    loadMoreBtn.on('click', function() {
        let btn = $(this);
        let offset = btn.data('offset');
        let table = getTableInstance(); 
        
        if (!table) {
             alert("Tabel sedang dimuat atau gagal dimuat. Silakan coba lagi sebentar.");
             return;
        }

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memuat...');

        $.ajax({
            url: "{{ route('mahasiswa.loadMore') }}",
            type: 'GET',
            data: { offset: offset },
            success: function(response) {
                let firstNewNode = null;
                
                if (response.mahasiswas && response.mahasiswas.length > 0) {
                    response.mahasiswas.forEach(function(mahasiswa) {
                        let editUrl = '{{ url("admin/mahasiswa") }}/' + mahasiswa.id + '/edit';
                        let deleteUrl = '{{ url("admin/mahasiswa") }}/' + mahasiswa.id;
                        let csrfToken = '{{ csrf_token() }}';
                        let actionButtons = `
                            <form action="${deleteUrl}" method="POST" class="d-inline">
                                <a href="${editUrl}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="return confirm('Yakin hapus?')"><i class="fa fa-lg fa-fw fa-trash"></i></button>
                            </form>`;

                        let rowNode = table.row.add([
                            mahasiswa.id, mahasiswa.nim, mahasiswa.nama_mahasiswa,
                            mahasiswa.jenis_kelamin, mahasiswa.angkatan,
                            mahasiswa.prodi ? mahasiswa.prodi.nama_prodi : 'N/A', 
                            actionButtons
                        ]).draw(false).node(); 
                        $(rowNode).find('td').addClass('align-middle');
                        $(rowNode).find('td:last-child').addClass('text-nowrap'); 
                        if (!firstNewNode) firstNewNode = rowNode;
                    });

                    if (firstNewNode) {
                        setTimeout(function() {
                             $('html, body').animate({ scrollTop: $(firstNewNode).offset().top - 70 }, 500);
                        }, 100);
                    }

                    btn.data('offset', response.new_offset);
                    if (response.remaining <= 0) btn.hide();
                    showLessBtn.show(); 
                } else {
                     btn.hide(); 
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText); 
                alert('Gagal memuat data. Periksa console.');
            },
            complete: function() {
                 btn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i> Tampilkan Lebih');
                 if (btn.data('offset') >= {{ $mahasiswa_count }}) { 
                     btn.hide();
                 }
            }
        });
    });

    // --- LOGIKA TOMBOL SHOW LESS --- (Kembali ke 'limit' data awal)
    showLessBtn.on('click', function() {
        let table = getTableInstance(); 
        if (!table) {
             alert("Tabel sedang dimuat atau gagal dimuat. Silakan coba lagi sebentar.");
             return;
        }

        let rowCount = table.rows().count();
        
        if (rowCount > limit) {
            // DAPATKAN INDEKS SEBAGAI ARRAY JS
            let allIndexesArray = table.rows().indexes().toArray(); 
            
            // AMBIL INDEKS YANG AKAN DIHAPUS (DARI 'limit' SAMPAI AKHIR)
            let indicesToRemove = allIndexesArray.slice(limit); // <-- Gunakan slice pada array
            
            // HAPUS BARIS BERDASARKAN INDEKS
            table.rows(indicesToRemove).remove().draw();

            // Reset offset load more dan tampilkan tombolnya
            loadMoreBtn.data('offset', limit).show(); 
            
            // Sembunyikan tombol show less
            $(this).hide(); 
        } else {
             // Jika data memang sudah <= limit
             $(this).hide();
        }
    });
});
</script>
@stop

