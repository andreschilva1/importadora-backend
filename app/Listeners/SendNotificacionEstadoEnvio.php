<?php

namespace App\Listeners;

use App\Events\NotificacionEstadoEnvio;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificacionEstadoEnvio
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificacionEstadoEnvio $evento): void
    {
        $envio = $evento->envio;
        $paquete = $envio->paquete;
        $cliente = $paquete->cliente;

        //Log::info($cliente->token_android);
        
        $client = new Client();
        $headers = [
          'Authorization' => config('firebase.firebaseKey'),
          'Content-Type' => 'application/json'
        ];
        $body = '{
          "to": "'.$cliente->token_android.'",
          "notification": {
            "title": "Actualizacion del envio"
            "body": "su paquete con # '.$paquete->codigo_rastreo.' paso a estado: '.$envio->envioEstado->name.'",
            "image": "'.$paquete->photo_path.'"
          },
          "data": {
            "paquete_id": "'.$paquete->id.'",
            "peso": "'. $paquete->peso .'",
            "type": "estado-envio",
          }
        }';
        $request = new Request('POST', 'https://fcm.googleapis.com/fcm/send', $headers, $body);
        $res = $client->sendAsync($request)->wait();
    }
}
