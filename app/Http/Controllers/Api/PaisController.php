<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pais;
use Illuminate\Http\Request;

class PaisController extends Controller
{
    public function getPaises()
    {
        try {
            $pais = Pais::get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $pais], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
}
