<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seguimiento;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Request as RequestGuzzle;
use GuzzleHttp\Client;

class SeguimientoController extends Controller
{
    function getCarriers(){
        try {
            $client = new Client();
            $request = new RequestGuzzle('GET', 'https://res.17track.net/asset/carrier/info/apicarrier.all.json');
            $res = $client->sendAsync($request)->wait();
            $json =$res->getBody()->getContents();
            $carriers = Seguimiento::getTransportistas($json);
            return response()->json($carriers, $res->getStatusCode());
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    function registrarNumeroTraking(Request $request)
    {
        try {

            $numeroRastreo = $request->numeroRastreo;
            $client = new Client();
            $headers = [
                '17token' =>  config('17track.token'),
                'Content-Type' => 'application/json'
            ];
            $body = '[
                {
                    "number": "' . $numeroRastreo . '" 
                }
            ]';
            $request = new RequestGuzzle('POST', 'https://api.17track.net/track/v2/register', $headers, $body);
            $res = $client->sendAsync($request)->wait();
            return response()->json(json_decode($res->getBody()->getContents()), $res->getStatusCode());
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    function getTrackInfo(Request $request)
    {
        try {

            $numeroRastreo = $request->numeroRastreo;
            $client = new Client();
            $headers = [
                '17token' => config('17track.token'),
                'content-type' => 'application/json'
            ];
            $body = '[
              {
                "number": "' . $numeroRastreo . '" 
              }
            ]';
            $request = new RequestGuzzle('POST', 'https://api.17track.net/track/v2/gettrackinfo', $headers, $body);
            $res = $client->sendAsync($request)->wait();
            $json =$res->getBody()->getContents();
            
            $seguimiento = new Seguimiento($json);
            return response()->json($seguimiento, $res->getStatusCode());

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    function changeCarrier(Request $request)
    {
        try {

            $numeroRastreo = $request->numeroRastreo;
            $carrierOld = $request->carrierOld;
            $carrierNew = $request->carrierNew;

            $client = new Client();
            $headers = [
                '17token' => config('17track.token'),
                'content-type' => 'application/json'
            ];

            $body = '[
                {
                  "number": "' . $numeroRastreo . '",
                  "carrier_old": "'.$carrierOld.'",
                  "carrier_new": "'.$carrierNew.'",
                  "final_carrier_old": 0,
                  "final_carrier_new": 0
                }
              ]';
              $request = new RequestGuzzle('POST', 'https://api.17track.net/track/v2/changecarrier', $headers, $body);
              $res = $client->sendAsync($request)->wait();

            return response()->json(json_decode($res->getBody()->getContents()) , $res->getStatusCode());

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }


}
