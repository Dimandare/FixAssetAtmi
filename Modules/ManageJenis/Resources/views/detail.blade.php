@extends('layouts.layout_main')
@section('title', 'Data Detail Jenis')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Detail Jenis</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('managejenis.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Detail Jenis</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h4 class="m-0 text-center text-purple lead">Kelompok :
                                    <span class="badge bg-purple">{{ $kelompok->kode_kelompok }}</span>
                                    {{ $kelompok->nama_kelompok }}
                                </h4>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-6">
                                        <h3 class="m-0 text-center" id="nama_jenis"><i class="fas fa-tags"></i>
                                            {{ $jenis->nama_jenis }}
                                        </h3>
                                        <h4 class="m-0 text-center">
                                            <span class="badge badge-primary"><i class="fas fa-barcode"></i> Kode :
                                                {{ $jenis->kode_jenis }}</span>
                                        </h4>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0)" id="btn-edit-jenis" title="Ubah Jenis"
                                            data-di="{{ $jenis->id_jenis }}" class="btn btn-sm btn-secondary float-right">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <!-- Main content -->
                <div class="card card-primary-outline">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3 class="card-title">Kelompok</h3>
                            </div>
                            <div class="col-6">
                                <div class="float-right">
                                    <select class="form-control form-control-sm" id="mode-selector">
                                        <option value="yayasan">Yayasan</option>
                                        <option value="smkmikael">SMK Mikael</option>
                                        <option value="politeknik">Politeknik</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tbl_jenis" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Kode</th>
                                    <th><i class="fas fa-bars"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="index_{{ $kelompok->id_kelompok }}"
                                    data-nama-kelompok-yayasan="{{ $kelompok->nama_kelompok_yayasan }}"
                                    data-nama-kelompok-smkmikael="{{ $kelompok->nama_kelompok_mikael }}"
                                    data-nama-kelompok-politeknik="{{ $kelompok->nama_kelompok_politeknik }}">
                                    <td>{{ $kelompok->id_kelompok }}</td>
                                    <td class="nama-kelompok">{{ $kelompok->nama_kelompok }}</td>
                                    <td class="text-center lead">
                                        <span class="badge badge-warning">{{ $kelompok->kode_kelompok }}</span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" id="btn-detail-jenis"
                                            data-di="{{ $kelompok->id_kelompok }}" title="Detail Jenis"
                                            class="btn btn-sm btn-light">
                                            <i class="far fa-folder-open"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Kode </th>
                                    <th><i class="fas fa-bars"></i></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.content -->
            </div>
        </section>
    </div>
    @include('managejenis::modal-edit')

@endsection
@section('scripttambahan')
    <!-- Page specific script -->
    <script>
        $(document).ready(function() {
            // Handle mode change event
            $('#mode-selector').change(function() {
                let selectedMode = $(this).val();

                // Update the "Nama Kelompok" column based on the selected mode
                $('#tbl_jenis tbody tr').each(function() {
                    let namaKelompok;
                    switch (selectedMode) {
                        case 'yayasan':
                            namaKelompok = $(this).data('nama-kelompok-yayasan');
                            break;
                        case 'smkmikael':
                            namaKelompok = $(this).data('nama-kelompok-smkmikael');
                            break;
                        case 'politeknik':
                            namaKelompok = $(this).data('nama-kelompok-politeknik');
                            break;
                    }
                    $(this).find('.nama-kelompok').text(namaKelompok);
                });
            });

            // Trigger the mode change event to initialize table with the default mode
            $('#mode-selector').trigger('change');

            $("#tbl_jenis").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tbl_jenis_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection