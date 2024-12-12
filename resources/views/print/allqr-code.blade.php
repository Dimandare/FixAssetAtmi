<div class="content-wrapper" style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; height: auto; padding: 20px;">
    <div class="content" style="width: 100%; display: flex; justify-content: center; align-items: flex-start; flex-wrap: wrap;">
        <div class="container-fluid">
            <!-- Loop through each asset -->
            <div class="row justify-content-center">
                @foreach ($assets as $asset)
                    <div class="col-md-6 mb-4" style="display: flex; justify-content: center; align-items: center; margin-top: 0.15in; margin-bottom: 0.15in;">
                        <div class="card" style="display: flex; flex-direction: row; border: 1px solid #ddd; border-radius: 8px; padding: 10px; max-width: 7.8in; width: 100%; height: 1.6in; margin: 0 auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); page-break-inside: avoid;">
                            <!-- QR Code Section -->
                            <div class="qr-code" style="flex-shrink: 0; width: 1.8in; height: 1.6in; margin-right: 22px; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                                <img src="data:image/png;base64,{{ $asset['qrCode'] }}" alt="QR Code" style="width: 100%; height: auto; object-fit: contain;">
                            </div>

                            <!-- Text Description Section -->
                            <div class="description" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; line-height: 1.2;">
                                <p style="font-size: 0.85rem; margin: 0;"><b>{{ $fa->nama_barang }}</b></p>
                                <p style="font-size: 0.85rem; margin: 0;"><b>Unit ke&nbsp;{{ $asset['unitke'] }}</b></p>

                                <p style="font-size: 0.75rem; margin: 0; text-align: left;">
                                    <b>Kode FA :</b> <span style="font-weight: bold; color: white; background-color: grey; padding: 2px 8px; border-radius: 5px;">{{ $asset['kodebaru'] }}</span><br>
                                    <b>Lokasi :</b> <span>{{ $fa->lokasi->nama_lokasi_yayasan }}</span><br>
                                    <b>Unit :</b> <span>{{ $fa->Institusi->nama_institusi }}</span><br>
                                    <b>Kelompok :</b> <span>{{ $fa->Kelompok->nama_kelompok_yayasan }}</span><br>
                                    <b>Jenis :</b> <span>{{ $fa->Jenis->nama_jenis_yayasan }}</span><br>
                                    <b>Ruang :</b> <span>{{ $fa->Ruang->nama_ruang_yayasan }}</span><br>
                                    <b>Type :</b> <span>{{ $fa->Tipe->nama_tipe_yayasan }}</span><br>
                                </p>
                                <p class="text-muted" style="font-size: 0.65rem; margin: 0;">{{ $fa->deskripsi_barang }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Print-specific CSS -->
<style>
    @page {
        size: legal; /* Set paper size to legal */
        margin: 0; /* Set margin to 0 for full-page printing */
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        .content-wrapper {
            display: block;
            text-align: center;
            width: 100%;
            margin: 0;
            page-break-inside: avoid;
        }

        .container-fluid {
            display: inline-block;
            width: auto;
        }

        .card {
            display: flex;
            flex-direction: row;
            margin: 0 auto;
            box-shadow: none;
            width: 7.8in;
            height: 1.6in;
            page-break-inside: avoid;
        }

        .qr-code {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 1.8in;
            height: 100%; /* Ensure it fills the height of the card */
            margin-right: 12px;
        }

        .btn-primary {
            display: none; /* Hide print button when printing */
        }

        .description {
            overflow-wrap: break-word;
            word-wrap: break-word;
        }
    }
</style>
<!-- Auto Print PDF Logic -->
<script>
    window.onload = function() {
        window.print(); // Automatically trigger the print dialog when the page loads
        
        // Event listener untuk kembali ke halaman sebelumnya setelah dialog print selesai
        window.onafterprint = function() {
            window.history.back(); // Go back to the previous page
        };
    };
</script>
