<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EnvioEstado;
use Illuminate\Http\Request;

class EstadoEnvioController extends Controller
{
    public function getEstadoEnvio()
    {
        try {
            $envio = EnvioEstado::get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $envio], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
}
