<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesaResource;
use App\Models\IndonesiaVillage;
use Illuminate\Http\Request;
use Laravolt\Indonesia\IndonesiaService;
use Illuminate\Support\Facades\Validator;

class DesaController extends Controller
{
    public function index()
    {
        $indonesiaService = new IndonesiaService();
        //get desa
        $desa = $indonesiaService->paginateVillages($numRows = 15);

        //return collection of desa as a resource
        return new DesaResource(true, 'List Data Desa', $desa);
    }

    function show($villageId)
    {
        $indonesiaService = new IndonesiaService();
        //get desa
        $desa = $indonesiaService->findVillage($villageId, $with = null);

        //return collection of desa as a resource
        return new DesaResource(true, 'List Data Desa', $desa);
    }

    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'code'     => 'required',
            'district_code'   => 'required',
            'name'   => 'required',
            'meta'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create desa
        $desa = IndonesiaVillage::create([
            'code'     => $request->code,
            'district_code'   => $request->district_code,
            'name'   => $request->name,
            'meta'   => $request->meta,
        ]);

        //return response
        return new DesaResource(true, 'Data Desa Berhasil Ditambahkan!', $desa);
    }

    public function update(Request $request, IndonesiaVillage $desa)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'code'     => 'required',
            'district_code'   => 'required',
            'name'   => 'required',
            'meta'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $desa->update([
            'code'     => $request->code,
            'district_code'   => $request->district_code,
            'name'   => $request->name,
            'meta'   => $request->meta,
        ]);

        //return response
        return new DesaResource(true, 'Data Desa Berhasil Diubah!', $desa);
    }

    public function destroy(IndonesiaVillage $desa)
    {
        $desa->delete();

        //return response
        return new DesaResource(true, 'Data Desa Berhasil Dihapus!', null);
    }
}
