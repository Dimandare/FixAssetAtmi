<?php

namespace Modules\ManageAset\Http\Controllers;

use App\Imports\ImportItem;
use App\Models\Divisi;
use App\Models\FixedAsset;
use App\Models\Institusi;
use App\Models\Jenis;
use App\Models\Kelompok;
use App\Models\Lokasi;
use App\Models\Ruang;
use App\Models\Tipe;
use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


class ManageAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $menu = 'Aset';

    public function index(Request $request)
    {
        $institusi = Institusi::where('id_institusi', '!=', 8)->get();
        $tipe = Tipe::all();
        $lokasi = Lokasi::all();

        $query = FixedAsset::query();
        if ($request) {
            //aset where request
            if ($request->nama_barang) {
                $query->orWhere('nama_barang', 'LIKE', '%' . $request->nama_barang . '%');
            }
            if ($request->id_institusi) {
                $query->orWhere('id_institusi', $request->id_institusi);
            }
            if ($request->id_divisi) {
                $query->orWhere('id_divisi', $request->id_divisi);
            }
            if ($request->id_tipe) {
                $query->orWhere('id_tipe', $request->id_tipe);
            }
            if ($request->id_jenis) {
                $query->orWhere('id_jenis', $request->id_jenis);
            }
            if ($request->id_kelompok) {
                $query->orWhere('id_kelompok', $request->id_kelompok);
            }
            if ($request->id_lokasi) {
                $query->orWhere('id_lokasi', $request->id_lokasi);
            }
            if ($request->id_ruang) {
                $query->orWhere('id_ruang', $request->id_ruang);
            }
            if ($request->tahun_diterima) {
                $query->orWhere('tahun_diterima', $request->tahun_diterima);
            }
            $aset = $query->orderBy('kode_fa')->get();
        } else {
            $aset = $query->where('tahun_diterima', '>=', now()->subYears(10)->year)->orderBy('kode_fa')->get();
        }


        return view('manageaset::index', compact('aset', 'tipe', 'lokasi', 'institusi'), ['menu' => $this->menu]);
    }

    public function detail($kode_fa)
    {
        $fa = FixedAsset::where('kode_fa', $kode_fa)->first();
        $institusi = Institusi::all();
        $tipe = Tipe::all();
        $lokasi = Lokasi::all();
        $code = $kode_fa;
      // Define the base URL for your asset detail page
// Define the URL or text to encode in the QR code
$baseUrl = url('/aset/manageaset/detail/'. $kode_fa );
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

        return view("manageaset::detail", compact('fa', 'tipe', 'lokasi', 'institusi'), ['menu' => $this->menu,'barcode' => $base64QrCode, 'code' => $code]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $menu = "Tambah Aset";
        $institusi = Institusi::all();
        $divisi = Divisi::all();
        $tipe = Tipe::all();
        $lokasi = Lokasi::all();
        $ruang = Ruang::all();
        $jenis = Jenis::all();
        $kelompok = Kelompok::all();

        return view('manageaset::create', compact('tipe', 'divisi', 'lokasi', 'institusi','ruang' , 'institusi' , 'menu','jenis', 'kelompok'));
    }

    public function getDivisi(Request $request)
    {
        $divisi = Divisi::where('id_institusi', $request->id_institusi)->get();
        return response()->json($divisi);
    }

    public function getKelompok(Request $request)
    {
        $kelompok = Kelompok::where('id_tipe', $request->id_tipe)->get();
        return response()->json($kelompok);
    }

    public function getJenis(Request $request)
    {
        $jenis = Jenis::where('id_kelompok', $request->id_kelompok)->get();
        return response()->json($jenis);
    }

    public function getLokasi(Request $request)
    {
        $ruang = Lokasi::where('id_lokasi', $request->id_lokasi)->get();
        return response()->json($ruang);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //define validation rules
      dd($request->all());
    }

    private function isValidDataStructure($data)
    {
        // Tentukan struktur kolom yang diharapkan
        $expectedColumns = ['no', 'kode institusi', 'kode divisi', 'kode tipe', 'kode kelompok', 'kode jenis', 'kode lokasi', 'kode ruang', 'no permintaan SPA', 'tahun diterima', 'nama barang', 'deskripsi barang', 'status transaksi', 'status barang'];

        // Periksa apakah ada kolom tambahan dalam data
        $extraColumns = array_diff($data, $expectedColumns);
        //dd($data, $expectedColumns, $extraColumns);
        if (!empty($extraColumns)) {
            $errorMessage = 'Format Data salah. Kolom berikut tidak sesuai dengan yang diharapkan: ' . implode(', ', $extraColumns);
            return $errorMessage;
        }

        return null; // Validasi berhasil
    }

    public function upload(Request $request)
{
    try {
        if ($request->hasFile('file')) {
            $data = $request->file('file');
            // Import data dari CSV ke koleksi
            $cek = Excel::toArray(new ImportItem, $data);

            foreach ($cek as $item) {
                unset($item[0]); // Menghapus header jika ada
                foreach ($item as $row) {
                    // Validasi jumlah kolom
                    if (count($row) < 11) {
                        // Skip row or handle the error if there are not enough columns
                        continue;
                    }

                    // Temukan entitas di database
                    $id_lokasi = Lokasi::where('nama_lokasi', $row[0])->first();
                    $id_institusi = Institusi::where('nama_institusi', $row[1])->first();
                    $id_kelompok = Kelompok::where('nama_kelompok', $row[2])->first();
                    $id_jenis = Jenis::where('nama_jenis', $row[3])->first();
                    $id_ruang = Ruang::where('nama_ruang', $row[4])->first();
                    $id_tipe = Tipe::where('nama_tipe', $row[5])->first();

                    // Cek apakah semua entitas ditemukan
                    if (!$id_lokasi || !$id_institusi || !$id_kelompok || !$id_jenis || !$id_ruang || !$id_tipe) {
                        // Jika ada entitas yang tidak ditemukan, log atau tampilkan pesan kesalahan
                        // Misalnya, log atau beri tahu admin tentang data yang tidak valid
                        // Skipping the row
                        continue;
                    }

                    $idFa = Str::random(32);
                    $kode_max = FixedAsset::where('id_institusi', $id_institusi->id_institusi)
                        ->where('id_tipe', $id_tipe->id_tipe)
                        ->where('id_kelompok', $id_kelompok->id_kelompok)
                        ->where('id_jenis', $id_jenis->id_jenis)
                        ->where('id_lokasi', $id_lokasi->id_lokasi)
                        ->where('id_ruang', $id_ruang->id_ruang)
                        ->count();

                    $no_urut = str_pad($kode_max + 1, 3, '0', STR_PAD_LEFT);
                    $kode_fa = $id_lokasi->kode_lokasi . "." . $id_institusi->kode_institusi . "." .  $id_kelompok->kode_kelompok . "." . $id_jenis->kode_jenis . "." . $id_ruang->kode_ruang . "." . $id_tipe->kode_tipe . "-" . $no_urut;
                    $status_fa = auth()->user()->hasRole('superadmin') ? 1 : 0;
                    $status_transaksi = $row[9] === 'Pemindahan Baru' ? 'Pemindahan' : $row[9];

                    // Simpan data ke database
                    $aset = FixedAsset::create([
                        "id_fa" => $idFa,
                        "id_institusi" => $id_institusi->id_institusi,
                        "id_divisi" => auth()->user()->id_divisi,
                        "id_tipe" => $id_tipe->id_tipe,
                        "id_kelompok" => $id_kelompok->id_kelompok,
                        "id_jenis" => $id_jenis->id_jenis,
                        "id_lokasi" => $id_lokasi->id_lokasi,
                        "id_ruang" => $id_ruang->id_ruang,
                        "nama_barang" => $row[7] ?? 'Unknown',
                        "foto_barang" => '',
                        "tahun_diterima" => $row[6] ?? 'Unknown',
                        "des_barang" => $row[8] ?? 'No description',
                        "no_permintaan" => '',
                        "status_transaksi" => $status_transaksi,
                        "status_barang" => $row[10] ?? 'Unknown',
                        "id_user" => auth()->user()->id,
                        "kode_fa" => $kode_fa,
                        "status_fa" => $status_fa
                    ]);
                }
            }

            return redirect()->route('manageaset.index')->with('notification', 'Data berhasil diupload');
        } else {
            return redirect()->back()->with('error', 'File not uploaded.');
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id_fa)
    {
        $aset = FixedAsset::findOrFail($id_fa);
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Sset',
            'data'    => $aset
        ]);
    }
 
    public function download($data)
    {
        $code = $data;
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);

        return response($barcode, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="barcode.png"');
    }


    public function validateAsset($kode_fa)
    {
        $fa = FixedAsset::where('kode_fa', $kode_fa)->first();
        // Pastikan $fa tidak null sebelum melakukan update
        if ($fa) {
            // Update fa
            $fa->update([
                'status_fa' => 1,
            ]);

            return response()->json(['message' => 'Validasi Aset Berhasil']);
        } else {
            return response()->json(['message' => 'Aset tidak ditemukan'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id_fa)
    {
        $fa = FixedAsset::findOrFail($id_fa);
        $institusi = Institusi::all();
        $divisi = Divisi::where('id_institusi', $fa->id_institusi)->get();
        $tipe = Tipe::all();
        $kelompok = Kelompok::where('id_tipe', $fa->id_tipe)->get();
        $jenis = Jenis::where('id_kelompok', $fa->id_kelompok)->get();
        $lokasi = Lokasi::all();
        $ruang = Ruang::where('id_ruang', $fa->id_ruang)->get();
        return view("manageaset::edit", compact('fa', 'institusi', 'divisi', 'tipe', 'kelompok', 'jenis', 'lokasi', 'ruang'), ['menu' => $this->menu]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id_fa)
    {
        $fa = FixedAsset::findOrFail($id_fa);

        //define validation rules
        $request->validate([
            "id_institusi" => 'required',
            "id_divisi" => 'required',
            "id_tipe" => 'required',
            "id_kelompok" => 'required',
            "id_jenis" => 'required',
            "id_lokasi" => 'required',
            "id_ruang" => 'required',
            "nama_barang" => 'required',
            "tahun_diterima" => 'required',
            "des_barang" => 'required',
            "status_transaksi" => 'required',
            "status_barang" => 'required',
            "foto_barang" => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama jika ada
            if ($fa->foto_barang) {
                Storage::disk('public')->delete('foto_barang/' . $fa->foto_barang);
            }

            $file = $request->file('foto_barang');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('foto_barang', $fileName, 'public');
        } else {
            $fileName = $fa->foto_barang;
        }

        $fa->update([
            "id_institusi" => $request->id_institusi,
            "id_divisi" => $request->id_divisi,
            "id_tipe" => $request->id_tipe,
            "id_kelompok" => $request->id_kelompok,
            "id_jenis" => $request->id_jenis,
            "id_lokasi" => $request->id_lokasi,
            "id_ruang" => $request->id_ruang,
            "nama_barang" => $request->nama_barang,
            "foto_barang" => $fileName,
            "tahun_diterima" => $request->tahun_diterima,
            "des_barang" => $request->des_barang,
            "no_permintaan" => $request->no_permintaan,
            "status_transaksi" => $request->status_transaksi,
            "status_barang" => $request->status_barang,
        ]);

        //return route manageaset.detail 
        return redirect()->route("manageaset.detail", ['kode_fa' => $fa->kode_fa])->with(['success' => 'Data Berhasil Disimpan. Terima kasih']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}