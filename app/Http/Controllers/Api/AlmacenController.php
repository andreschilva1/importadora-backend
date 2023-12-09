<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmacenController extends Controller
{
    public function obtenerAlmacenes()
    {
        try {
            $almacenes = Almacen::with("pais")->get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $almacenes], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function crearAlmacen(Request $request)
    {
        try {
            DB::beginTransaction();
            $almacen = new Almacen();
            $almacen->name = $request->name;
            $almacen->direccion = $request->direccion;
            $almacen->pais_id = $request->pais_id;
            $almacen->save();
            DB::commit();
            return response()->json(['mensaje' => 'Almacén creado exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function editAlmacen(Request $request)
    {
        try {
            $almacen = Almacen::where("id", $request->id)->with("pais")->first();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $almacen], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function eliminarAlmacen(Request $request)
    {
        try {
            DB::beginTransaction();
            $almacen = Almacen::findOrFail($request->id);
            $almacen->delete();
            DB::commit();
            return response()->json(['mensaje' => 'Almacén eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
}
