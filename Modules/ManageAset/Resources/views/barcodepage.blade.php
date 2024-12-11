<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Detail</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: left;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .asset-image {
            width: 100%;
            height: 200px;
            background-size: cover;
        }
        .asset-details {
            padding: 15px;
        }
        .asset-details h2 {
            margin-top: 0;
            font-size: 18px;
            color: #333;
            text-transform: uppercase;
        }
        .asset-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .asset-details table th, .asset-details table td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .asset-details table th {
            font-weight: bold;
            color: #555;
        }
        .asset-details table td {
            color: #333;
        }
        .asset-details table td a {
            color: #007bff;
            text-decoration: none;
        }
        .asset-details table td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>Asset Management</h1>
        </div>
        <br>
        <!-- Image Section -->
        <div class="asset-image" style="background: url('{{ asset('boxs.png') }}') no-repeat center center; background-size: cover;"></div>

        <!-- Asset Details Section -->
        <div class="asset-details">
            <h2>Detail Asset</h2>
            <table>
                <tr>
                    <th>ID Asset</th>
                    <td style="color: #007bff;">
                        @if(isset($kodebaru) && $kodebaru != null)
                            {{ $kodebaru }}
                        @else
                            {{ $fa->kode_fa }}
                        @endif

                    </td>
                </tr>
                @if(isset($kodebaru) && $kodebaru != null)

                <tr>
                    <th>Unit Ke</th>
                    <td style="color: #007bff;">
                       
                            {{ $unitke }}
                    </td>
                </tr>
                @else
                <tr>
                    <th>Total Unit</th>
                    <td style="color: #007bff;">
                     {{ $fa->jumlah_unit }}
                    </td>
                </tr>
                @endif

                <tr>
                    <th>Nama Asset</th>
                    <td>{{ ucfirst($fa->nama_barang) }}</td>
                </tr>
                <tr>
                    <th>Deskripsi Asset</th>
                    <td>{{ ucfirst($fa->des_barang) }}</td>
                </tr>
                <tr>
                    <th>Tanggal Kepemilikan</th>
                    <td>{{ $fa->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                </tr>
                <tr>
                    <th>Jenis</th>
                    <td>{{ $fa->jenis->nama_jenis_yayasan }}</td>
                </tr>
                <tr>
                    <th>Kelompok</th>
                    <td>{{ $fa->kelompok->nama_kelompok_yayasan }}</td>
                </tr>
                <tr>
                    <th>Tipe</th>
                    <td>{{ $fa->tipe->nama_tipe_yayasan }}</td>
                </tr>
                <tr>
                    <th>Kondisi</th>
                    <td><a href="#">{{ $fa->status_barang }}</a></td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
