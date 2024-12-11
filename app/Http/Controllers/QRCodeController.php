<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\FixedAsset;

class QRCodeController extends Controller
{
    public function generateQRCode(Request $request)
    {
        $fa = FixedAsset::where('kode_fa', $request->kode_fa)->first();
    
        if (!$fa) {
            return redirect()->back()->withErrors('Fixed asset not found.');
        }
    
        if ($request->action === 'single') {
            $kode = $fa->kode_fa;
    
            // Potong kode asli menjadi hanya bagian yang diperlukan
            $kode_awal = substr($kode, 0, -7);
    
            // Ambil jumlah unit
            $jumlah_unit = $request->unit;
    
            // Ubah jumlah unit menjadi 3 digit (misalnya, 3 menjadi 003)
            $jumlah_unit_formatted = str_pad($jumlah_unit, 3, '0', STR_PAD_LEFT);
    
            // Gabungkan kode awal dengan jumlah unit
            $kode_baru = $kode_awal . $jumlah_unit_formatted;
    
            // Generate URL for QR code
            $baseUrl = route('aset.detailbarcode', ['kode_fa' => $fa->kode_fa, 'kode_baru' => $kode_baru]);
            $fullUrl = $baseUrl;
    
            // Create a new QR code instance
            $qrCode = new QrCode($fullUrl);
    
            // Set the size of the QR code (in pixels)
            $qrCode->setSize(200); // 200x200 pixels
    
            // Set the margin around the QR code
            $qrCode->setMargin(10); // 10 pixels margin
    
            // Generate the QR code
            $writer = new PngWriter();
            $qrCodeImage = $writer->write($qrCode)->getString();
    
            // Encode QR code image in Base64 for embedding in HTML
            $base64QrCode = base64_encode($qrCodeImage);
    
            return view('print.singleqr-code', [
                'qrCode' => $base64QrCode,
                'asset' => $fa,
                'kodebaru' => $kode_baru,
                'unitke' => $request->unit
            ]);
        }
    
        if ($request->action === 'all') {
            $kode_awal = substr($fa->kode_fa, 0, -7);
            $jumlah_unit = $fa->jumlah_unit;
            $qrCodes = [];
        
            for ($i = 1; $i <= $jumlah_unit; $i++) {
                // Ubah nomor unit menjadi 3 digit
                $unit_formatted = str_pad($i, 3, '0', STR_PAD_LEFT);
        
                // Gabungkan kode awal dengan unit
                $kode_baru = $kode_awal . $unit_formatted;
        
                // Generate URL for QR code
                $baseUrl = route('aset.detailbarcode', ['kode_fa' => $fa->kode_fa, 'kode_baru' => $kode_baru]);
                $fullUrl = $baseUrl;
        
                // Generate QR code for each unit
                $qrCode = new QrCode($fullUrl);
                $qrCode->setSize(200);
                $qrCode->setMargin(10);
        
                // Write QR code and encode as Base64
                $writer = new PngWriter();
                $qrCodeImage = $writer->write($qrCode)->getString();
                $base64QrCode = base64_encode($qrCodeImage);
        
                $qrCodes[] = [
                    'kodebaru' => $kode_baru,
                    'unitke' => $i,
                    'qrCode' => $base64QrCode
                ];
            }
        
        
            return view('print.allqr-code', [
                'assets' => $qrCodes,
                'fa' => $fa
            ]);
        }
        
    
        return redirect()->back()->withErrors('Invalid QR code generation request.');
    }
    
    

      
    private function generateQRCodeForUnit($fa, $unit)
    {
        // Menyiapkan data untuk QR Code, termasuk kode_fa dan unit
        $data = [
            'kode_fa' => $fa->kode_fa,
            'unit' => $unit,
        ];

        // Menghasilkan QR Code dan mengembalikan data QR Code beserta kode unit
        return [
            'qr_code' => QrCode::size(250)->generate(json_encode($data)),
            'kode_unit' => 'Unit ' . $unit,
        ];
    }
}
