<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rastreo;
use Illuminate\Http\Request;

class RastreoController extends Controller
{
    function getRastreos(Request $request)
    {
        try {
            $user= $request->user();
            $rastreos = Rastreo::where('usuario_id', $user->id)->get();
            return response()->json($rastreos, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    function resgistrar(Request $request)
    {
        try {
            $user= $request->user();
            $rastreo = new Rastreo();
            $rastreo->name = $request->name;
            $rastreo->codigo_rastreo = $request->codigoRastreo;
            $rastreo->usuario_id = $user->id;
            $rastreo->save();

            return response()->json('registrado exitosamente', 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }



   
}
