<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
   
    public function notify(Request $request)
    {
        //TODO notificar al usuario
        
        //Log::info($request->all());

        /* $paisOrigen = $request->data["track_info"]["shipping_info"]["shipper_address"]["country"];
        $paisDestino = $request->data["track_info"]["shipping_info"]["recipient_address"]["country"];
        $estadoActual = $request->data["track_info"]["latest_status"]["status"];
        $ultimaDescripcion = $request->data["track_info"]["latest_event"]["description"];
        $location = $request->data["track_info"]["latest_event"]["location"];
        $fechaDeRegistro = $request->data["track_info"]["latest_event"]["time_utc"];
        $diasEnTransito = $request->data["track_info"]["time_metrics"]["days_of_transit"];
        $eventos = $request->data["track_info"]["tracking"]["providers"][0]["events"]; */
                
    }
}

