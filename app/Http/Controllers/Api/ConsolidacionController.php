<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ConsolidacionController extends Controller
{

    public function obtenerPaquetesDeConsolidacion(Request $req)
    {
        try {
            $paquetes = Paquete::where('consolidado_id', $req->paqueteId)->get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
    
    function registrarConsolidacion(Request $req)
    {
        try {
            $userActual = $req->user();
            DB::beginTransaction();
            $paquete = Paquete::findOrFail($req->paqueteId);
            $paquete->peso = $req->peso;
            $paquete->photo_path = $req->photo_path;
            $paquete->codigo_rastreo = $req->codigo_rastreo;
            $paquete->empleado_id = $userActual->id;
            $paquete->consolidacion_estado_id = 2;
            $paquete->save();
            DB::commit();


            return response()->json(['mensaje' => 'Paquete creado exitosamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }

    function createConsolidacion(Request $req)
    {
        try {
            $userActual = $req->user();
            DB::beginTransaction();
            $paquete = new Paquete();
            $paquete->peso = $req->peso;
            $paquete->cliente_id = $userActual->id;
            $paquete->almacen_id = $req->almacenId;
            $paquete->empleado_id = null;
            $paquete->consolidacion_estado_id = 1;
            $paquete->save();

            $paquetesIds = $req->lista_ids_paquetes;

            $paquetes = Paquete::whereIn('id', $paquetesIds)->get();
            foreach ($paquetes as $paq) {
                $paq->consolidado_id = $paquete->id;
                $paq->save();
            }

            $paquete->save();
            DB::commit();

            return response()->json(['mensaje' => 'Consolidación creado exitosamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }

    function editConsolidacion(Request $req)
    {
        try {
            DB::beginTransaction();
            $paquete = Paquete::findOrFail($req->paqueteId);
            $paquete->peso = $req->peso;
            $paquete->save();

            $paquetes = Paquete::where('consolidado_id', $req->paqueteId)->get();
            foreach ($paquetes as $paq) {
                $paq->consolidado_id =  null;
                $paq->save();
            }

            $paquetesIds = $req->lista_ids_paquetes;

            $paquetesEditar = Paquete::whereIn('id', $paquetesIds)->get();
            foreach ($paquetesEditar as $paq) {
                $paq->consolidado_id = $paquete->id;
                $paq->save();
            }

            $paquete->save();
            DB::commit();

            return response()->json(['mensaje' => 'Consolidación editado exitosamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }
}
