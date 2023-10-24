<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MetodoEnvio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MetodoEnvioController extends Controller
{
    public function getMetodoEnvio()
    {
        try {
            $envio = MetodoEnvio::get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $envio], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function createMetodoEnvio(Request $request)
    {
        try {
            DB::beginTransaction();
            $envio = new MetodoEnvio();
            $envio->transportista = $request->transportista;
            $envio->metodo = $request->metodo;
            $envio->costo_kg = $request->costo_kg;
            $envio->save();
            DB::commit();
            return response()->json(['mensaje' => 'Método de envio creado exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function editMetodoEnvio(Request $request)
    {
        try {
            $envio = MetodoEnvio::where("id", $request->id)->first();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $envio], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    // public function storeMetodoEnvio(Request $request)
    // {
    //     try {

    //         $envio->transportista = $request->transportista;
    //         $envio->metodo = $request->metodo;
    //         $envio->costo_kg = $request->costo_kg;
    //         $envio->save();
    //         DB::commit();
    //         return response()->json(['mensaje' => 'Método de envio creado exitosamente'], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['mensaje' => $e->getMessage()], 500);
    //     }
    // }

    public function deleteMetodoEnvio(Request $request)
    {
        try {
            DB::beginTransaction();
            $envio = MetodoEnvio::findOrFail($request->id);
            $envio->delete();
            DB::commit();
            return response()->json(['mensaje' => 'Método de envío eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
}
