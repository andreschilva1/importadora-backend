<?php

namespace App\Listeners;

use App\Events\NotificacionPaquete;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNotificacionPaquete
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
    public function handle(NotificacionPaquete $evento): void
    {
        $paquete = $evento->paquete;
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
            "title": "Registro de paquete"
            "body": "tiene un nuevo paquete registrado con el codigo de rastreo: '.$paquete->codigo_rastreo.'",
            "image": "'.$paquete->photo_path.'"
          },
          "data": {
            "type": "registro-paquete",
          }
        }';
        $request = new Request('POST', 'https://fcm.googleapis.com/fcm/send', $headers, $body);
        $res = $client->sendAsync($request)->wait();
       
        
        
    }
}
