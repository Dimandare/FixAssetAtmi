<?php

namespace Modules\ManageInstitusi\Http\Controllers;

use App\Models\Divisi;
use App\Models\Institusi;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ManageInstitusiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected  $menu = 'Institusi';
    public function index()
    {
        //$menu = 'Institusi';
        $data = Institusi::get();
        return view('manageinstitusi::index')->with(['institusi' => $data, 'menu' => $this->menu]);
    }

    public function detail(string $id_institusi)
    {
        $institusi = Institusi::findOrFail($id_institusi);
        $divisi = Divisi::where('id_institusi', $id_institusi);
        return view("manageinstitusi::detail")->with(['ins' => $institusi, 'divisi' => $divisi, 'menu' => $this->menu]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('manageinstitusi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nama_institusi' => 'required',
        ]);

        //check validation 
        if ($validator->fails()) {
            return response()->json($validator->error(), 422);
        }

        //kode institusi bertambah sesuai nomor max di tabel
        $kode_max = Institusi::max('kode_institusi');
        $kode_baru = $kode_max + 1;

        //create INSTITUSI
        $institusi = Institusi::create([
            'nama_institusi' => $request->nama_institusi,
            'kode_institusi' => $kode_baru,
        ]);
        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Institusi Berhasil Disimpan.',
            'data'    => $institusi
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id_institusi)
    {
        $institusi = Institusi::find($id_institusi);
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Institusi',
            'data'    => $institusi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('manageinstitusi::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id_institusi)
    {
        $institusi = Institusi::findOrFail($id_institusi);
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nama_institusi' => 'required',
        ]);

        //check validation 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create INSTITUSI
        $institusi->update([
            'nama_institusi' => $request->nama_institusi,
        ]);

        //return response
        return response()->json([
            'success' => true,
            'message' => 'Data Institusi Berhasil Diubah.',
            'data'    => $institusi
        ]);
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