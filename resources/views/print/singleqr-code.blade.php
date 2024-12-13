<div class="content-wrapper" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div class="content" style="width: 100%; display: flex; justify-content: center; align-items: center;">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <!-- Card for QR Code and Description -->
                <div class="col-md-6 mb-4" style="display: flex; justify-content: center; align-items: center;">
                    <div class="card" style="display: flex; flex-direction: row; border: 1px solid #ddd; border-radius: 8px; padding: 10px; max-width: 7.8in; width: 100%; height: 1.6in; margin: 0 auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <!-- QR Code Section -->
                        <div class="qr-code " style="flex-shrink: 0; width: 1.8in; height: 1.6in; margin-right: 22px; display: flex; justify-content: center; align-items: center; overflow: hidden;">
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 100%; height: auto; object-fit: contain;">
                        </div>

                        <!-- Text Description Section -->
                        <div class="description" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; line-height: 1.2;">
                            <p style="font-size: 0.85rem; margin: 0;"><b>{{ $asset->nama_barang }}</b></p>
                            <p style="font-size: 0.85rem; margin: 0;"><b>Unit ke&nbsp;{{ $unitke }}</b></p>

                            <p style="font-size: 0.75rem; margin: 0; text-align: left;">
                                <b>Kode FA :</b> <span class="badge bg-info fs-7" style="font-weight: bold; padding: 2px 8px; border-radius: 5px;">{{ $kodebaru }}</span><br>
                                <b>Lokasi :</b> <span>{{ $asset->lokasi->nama_lokasi_yayasan }}</span><br>
                                <b>Unit :</b> <span>{{ $asset->Institusi->nama_institusi }}</span><br>
                                <b>Kelompok :</b> <span>{{ $asset->Kelompok->nama_kelompok_yayasan }}</span><br>
                                <b>Jenis :</b> <span>{{ $asset->Jenis->nama_jenis_yayasan }}</span><br>
                                <b>Ruang :</b> <span>{{ $asset->Ruang->nama_ruang_yayasan }}</span><br>
                                <b>Type :</b> <span>{{ $asset->Tipe->nama_tipe_yayasan }}</span><br>
                            </p>
                            <p class="text-muted" style="font-size: 0.65rem; margin: 0;">{{ $asset->deskripsi_barang }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Print-specific CSS -->
<style>
      @page {
            size: legal; /* Set paper size to legal */
            margin: 0; /* Optional: Set margin to 0 for full-page printing */
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
            height: auto;
            margin: 0;
            page-break-inside: avoid;  /* Avoid page break inside */
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
        }
        .qr-code {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 1.8in;
            height: 100%;  /* Ensure it fills the height of the card */
            margin-right: 12px;
        }
        .btn-primary {
            display: none; /* Hide print button when printing */
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
