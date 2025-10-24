@extends('adminlte::page')

@section('title', 'Data Kurikulum')

@section('content_header')
    <h1>Data Kurikulum</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Tombol Tambah --}}
            <div class="mb-3">
                 <a href="{{ route('kurikulum.create') }}" class="btn btn-primary">Tambah Kurikulum</a>
            </div>

            {{-- Definisikan Kolom Tabel --}}
            @php
            $heads = [
                'ID',
                'Kode Kurikulum',
                'Nama Kurikulum',
                'Tahun',
                'Keterangan',
                ['label' => 'Aksi', 'no-export' => true, 'width' => 'auto'],
            ];
            @endphp
            
            {{-- Komponen DataTable --}}
            <x-adminlte-datatable id="table-kurikulum" :heads="$heads" theme="light" striped hoverable bordered compressed with-buttons>
                 {{-- Data Awal --}}
                @foreach($kurikulums as $kurikulum)
                    <tr>
                        <td class="align-middle">{{ $kurikulum->id }}</td>
                        <td class="align-middle">{{ $kurikulum->kode }}</td>
                        <td class="align-middle">{{ $kurikulum->nama }}</td>
                        <td class="align-middle">{{ $kurikulum->tahun }}</td>
                        <td class="align-middle">{{ $kurikulum->keterangan}}</td>
                        <td class="align-middle text-nowrap">
                            <form action="{{ route('kurikulum.destroy', $kurikulum->id) }}" method="POST" class="d-inline delete-form">
                                <a href="{{ route('kurikulum.edit', $kurikulum->id) }}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>
                                {{-- Tombol Show bisa ditambahkan jika perlu --}}
                                {{-- <a href="{{ route('kurikulum.show', $kurikulum->id) }}" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                    <i class="fa fa-lg fa-fw fa-eye"></i>
                                </a> --}}
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
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
                    $current_count = $kurikulums->count();
                    $limit = 5; // Samakan dengan limit controller
                @endphp
                
                <button id="load-more-btn" class="btn btn-primary" 
                        data-offset="{{ $current_count }}" 
                        style="{{ ($current_count < $total_kurikulum) ? '' : 'display:none;' }}"> {{-- Gunakan $total_kurikulum --}}
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
    
    const loadMoreBtn = $('#load-more-btn');
    const showLessBtn = $('#show-less-btn');
    const limit = 5; 
    const tableId = '#table-kurikulum'; // Ganti ID tabel

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
            url: "{{ route('kurikulum.loadMore') }}", // Ganti ke kurikulum.loadMore
            type: 'GET',
            data: { offset: offset },
            success: function(response) {
                let firstNewNode = null;
                
                // Ganti ke response.kurikulums
                if (response.kurikulums && response.kurikulums.length > 0) { 
                    response.kurikulums.forEach(function(kurikulum) { // Ganti ke kurikulum
                        let editUrl = '{{ url("admin/kurikulum") }}/' + kurikulum.id + '/edit'; // Ganti ke kurikulum
                        let deleteUrl = '{{ url("admin/kurikulum") }}/' + kurikulum.id; // Ganti ke kurikulum
                        let csrfToken = '{{ csrf_token() }}';
                        let actionButtons = `
                            <form action="${deleteUrl}" method="POST" class="d-inline delete-form">
                                <a href="${editUrl}" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete"><i class="fa fa-lg fa-fw fa-trash"></i></button>
                            </form>`;

                        // Sesuaikan kolom untuk Kurikulum
                        let rowNode = table.row.add([
                            kurikulum.id, 
                            kurikulum.kode_kurikulum, 
                            kurikulum.nama_kurikulum,
                            kurikulum.tahun,
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
                 // Gunakan total_kurikulum dari PHP
                 if (btn.data('offset') >= {{ $total_kurikulum }}) { 
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
            let allIndexesArray = table.rows().indexes().toArray(); 
            let indicesToRemove = allIndexesArray.slice(limit); 
            
            table.rows(indicesToRemove).remove().draw();

            loadMoreBtn.data('offset', limit).show(); 
            $(this).hide(); 
        } else {
             $(this).hide();
        }
    });

     // Tambahkan konfirmasi sebelum submit form delete
    $(document).on('submit', '.delete-form', function(e) {
        if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            e.preventDefault(); 
        }
    });
});
</script>
@stop
