<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificacionEstadoEnvio;
use App\Http\Controllers\Controller;
use App\Models\Envio;
use App\Models\MetodoEnvio;
use App\Models\Pago;
use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnvioController extends Controller
{
    public function createEnvio(Request $request)
    {
        try {
            Db::beginTransaction();
            $envio = new Envio();
            $paquete = Paquete::where('id', $request->paquete_id)->first();
            $metodo = MetodoEnvio::where('id', $request->metodo_envio_id)->first();
            $envio->costo = (int)$paquete->peso * (int)$metodo->costo_kg;
            $envio->paquete_id = $request->paquete_id;
            $envio->metodo_envio_id = $request->metodo_envio_id;
            $envio->envio_estado_id = 1;
            $envio->save();

            $pago = new Pago();
            $pago->total = (int)$envio->costo;
            $pago->envio_id = $envio->id;
            $pago->save();
            DB::commit();
            return response()->json(['mensaje' => 'Envío creado exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function getEnvio(Request $request)
    {
        try {
            $envio = Envio::join('metodos_envios', 'envios.metodo_envio_id', 'metodos_envios.id')
                ->join('envios_estados', 'envios.envio_estado_id', 'envios_estados.id')
                ->select(
                    'envios.id',
                    'envios.codigo_rastreo',
                    'envios.costo',
                    'envios.paquete_id',
                    'envios.metodo_envio_id',
                    'metodos_envios.transportista',
                    'metodos_envios.metodo',
                    'metodos_envios.costo_kg',
                    'envios.envio_estado_id',
                    'envios_estados.name'
                )
                ->where("paquete_id", $request->id)->first();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $envio], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function storeEnvio(Request $request)
    {
        try {
            Db::beginTransaction();
            $envio = Envio::where("id", $request->id)->first();
            $envio->codigo_rastreo = $request->codigo_rastreo;
            $envio->envio_estado_id = $request->envio_estado_id;
            $envio->update();
            DB::commit();
            NotificacionEstadoEnvio::dispatch($envio);
            return response()->json(['mensaje' => 'Envío actualizado'], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
}
