<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Paquete;
use App\Utils\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class PaqueteController extends Controller
{

    function reconocerPaquete(Req $req)
    {
        try {
            $image = $req->file('imagen');
            $originalName = $image->getClientOriginalName();
            $path = $image->storeAs('', $originalName, 's3');
            $imegeUrl = Storage::disk('s3')->url($path);
            Utils::eliminarArchivosTemporales('s3');
            
            $client = new Client();

            $headers = [
                'Ocp-Apim-Subscription-Key' => 'd2a335ad99e3491db4e71010b2b107d1',
                'Content-Type' => 'application/json'
            ];


            $body = '{
             "urlSource": "' . $imegeUrl . '" 
            }';

            $analyzeLabelRequest = new Request('POST', 'https://reconocimientopaquetes.cognitiveservices.azure.com/formrecognizer/documentModels/EtiquetasPacketes:analyze?api-version=2023-07-31', $headers, $body);
            $analyzeLabelResponse = $client->sendAsync($analyzeLabelRequest)->wait();

            $headerResponse = $analyzeLabelResponse->getHeaders();

            //obtenemos el link de la solicitud para obtener los resultados 
            $endpointToGetResult = (string) $headerResponse["Operation-Location"][0];

            //esperamos 3 segundos para obtener los resultados
            sleep(5);
            $headers = [
                'Ocp-Apim-Subscription-Key' => 'd2a335ad99e3491db4e71010b2b107d1'
            ];

            $requestgetResult = new Request('GET', $endpointToGetResult, $headers);
            $responseResult = $client->sendAsync($requestgetResult)->wait();
            $responseBody = json_decode($responseResult->getBody());
            $fields = $responseBody->analyzeResult->documents[0]->fields;

            return response()->json(
                [
                    'nombre' => $fields->nombre->valueString,
                    'casillero' => $fields->casillero->valueString,
                    'direccion' => $fields->direccion->valueString,
                    'numeroRastreo' => $fields->numeroRastreo->valueString,
                    'photoPath' => $imegeUrl,
                ],
                $responseResult->getStatusCode()
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'mensaje' => $th->getMessage(),
                ],
                500
            );
        }
    }

    function createPaquete(Req $req) {
        try {
            DB::beginTransaction();
            $paquete = new Paquete();
            $paquete->photo_path = $req->photo_path;
            $paquete->codigo_rastreo = $req->codigo_rastreo;
            $paquete->peso = $req->peso;
            $paquete->cliente_id = $req->cliente_id;
            $paquete->almacen_id = $req->almacen_id;
            $paquete->empleado_id = $req->empleado_id;
            $paquete->save();
            DB::commit();

            return response()->json(['mensaje' => 'Paquete creado exitosamente'], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }
}
