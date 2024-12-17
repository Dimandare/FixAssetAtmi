@extends('layouts.layout_main')
@section('title', 'Edit Aset Tetap')
@section('scriptheadtambahan')
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-6">
                            <h1 class="m-0">Edit Data Aset</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <div class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Aset Tetap (Fixed Asset)</h3>
                            <span class="badge bg-info float-right fs-7">{{ $fa->kode_fa }}</span>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    {!! implode('', $errors->all('<li>:message</li>')) !!}
                                </div>
                            @endif
                            <form action="{{ route('manageaset.update', $fa->id_fa) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="id_fa">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Instansi</label>
                                            <select id="institusi" name="institusi" class="form-control" required>
                                                <option value="">- Pilih Instansi -</option>
                                                <option value="1">Yayasan</option>
                                                <option value="2">SMK Mikael</option>
                                                <option value="3">Politeknik</option>
                                            </select>
                                        </div>
                                    </div>
                            
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Lokasi</label>
                                            <select id="lokasi" name="id_lokasi" class="form-control select2" required>
                                                <option value="">- Pilih Lokasi -</option>
                                                @foreach ($lokasi as $lok)
                                                <option value="{{ $lok->id_lokasi }}" {{ $fa->id_lok }}>
                                                    {{ $lok->nama_lokasi_yayasan }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Ruang</label>
                                            <select id="ruang" name="id_ruang" class="form-control select2" required>
                                                <option value="">- Pilih Ruang -</option>
                                                @foreach ($ruang as $ru)
                                                <option value="{{ $ru->id_ruang }}" {{ $fa->id_ruang }}>
                                                    {{ $ru->nama_ruang_yayasan }}
                                                    {{ $ru->nama_ruang_mikael }}
                                                    {{ $ru->nama_ruang_politeknik }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Tipe</label>
                                            <select id="tipe" name="id_tipe" class="form-control select2" required>
                                                <option value="">- Pilih Tipe -</option>
                                                @foreach ($tipe as $ti)
                                                <option value="{{ $ti->id_tipe }}" {{ $fa->id_tipe }}>
                                                    {{ $ti->nama_tipe_yayasan }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Kelompok</label>
                                            <select id="kelompok" name="id_kelompok" class="form-control select2" required>
                                                <option value="">- Pilih Kelompok -</option>
                                                @foreach ($kelompok as $ke)
                                                <option value="{{ $ke->id_kelompok }}" {{ $fa->id_kelompok }}>
                                                    {{ $ke->nama_kelompok_yayasan }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                            
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Jenis</label>
                                            <select id="jenis" name="id_jenis" class="form-control select2" required>
                                                <option value="">- Pilih Jenis -</option>
                                                @foreach ($jenis as $je)
                                                <option value="{{ $je->id_jenis }}" {{ $fa->id_jenis }}>
                                                    {{ $je->nama_jenis_yayasan }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control" name="nama_barang" value="{{ $fa->nama_barang }}" id="nama_barang" required>
                                            <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Tahun Diterima</label>
                                            <input type="text" class="form-control" name="tahun_diterima" value="{{ $fa->tahun_diterima }}" data-inputmask='"mask": "9999"' data-mask>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <label for="formdes_barang">Deskripsi Detail Barang</label>
                                    <textarea class="form-control" rows="3" name="des_barang" id="formdes_barang">{!! $fa->des_barang !!}</textarea>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status Transaksi Barang</label>
                                            <select id="status_transaksi" class="form-control" name="status_transaksi" required>
                                                <option value="Pengadaan Baru" {{ 'Pengadaan Baru' == $fa->status_transaksi ? 'selected' : '' }}>Pengadaan Baru</option>
                                                <option value="Penjualan" {{ 'Penjualan' == $fa->status_transaksi ? 'selected' : '' }}>Penjualan</option>
                                                <option value="Pemindahan" {{ 'Pemindahan' == $fa->status_transaksi ? 'selected' : '' }}>Pemindahan</option>
                                            </select>
                                        </div>
                                    </div>
                            
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status Kondisi Barang</label>
                                            <select id="status_barang" class="form-control" name="status_barang" required>
                                                <option value="baik(100%)" {{ 'baik (100%)' == $fa->status_barang ? 'selected' : '' }}>Baik (100%)</option>
                                                <option value="cukup(50%)" {{ 'cukup (50%)' == $fa->status_barang ? 'selected' : '' }}>Cukup (50%)</option>
                                                <option value="rusak" {{ 'rusak' == $fa->status_barang ? 'selected' : '' }}>Rusak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- New input field for jumlah_unit -->
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="jumlah_unit">Jumlah Unit</label>
                                            <input type="number" class="form-control" name="jumlah_unit" value="{{ $fa->jumlah_unit }}" id="jumlah_unit" required min="1">
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-3">
                                        <img src="{{ $fa->foto_barang ? asset('fotofixaset/' . basename($fa->foto_barang)) : asset('boxs.png') }}" class="product-image" alt="Foto Barang Aset">
                                    </div>
                                    <div class="col-9">
                                        <div class="form-group">
                                            <label for="InputFile">File Foto Barang</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="customFile" name="foto_barang">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <hr>
                                <button type="submit" class="btn btn-primary btn-block">SIMPAN</button>
                            </form>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- /.content -->
        </div>
    </div>
@endsection
@section('scripttambahan')
    <!-- InputMask -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    
    
   
    @if (session('notification'))
        <script>
            var isi = @json(session('notification'));
            Swal.fire({
                icon: 'success',
                title: 'Hei...',
                text: isi,
                showConfirmButton: false,
                timer: 2500
            });
        </script>
    @endif
@endsection
