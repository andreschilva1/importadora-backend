<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificacionPaquete;
use App\Http\Controllers\Controller;
use App\Models\Paquete;
use App\Utils\Utils;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as Req;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;



class PaqueteController extends Controller
{
    public function obtenerPaquetes(Req $req)
    {
        try {
            $user = $req->user();
            $rol = $user->getRoleNames()->first();

            if ($rol == "Cliente") {
                $paquetes = Paquete::where('cliente_id', $user->cliente->id)->where('consolidado_id', null)->where('consolidacion_estado_id',null)->whereDoesntHave('envio')->get();
                return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
            } else {
                $paquetes = Paquete::where('consolidado_id', null)->where('consolidacion_estado_id',null)->whereDoesntHave('envio')->get();
                return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
    public function obtenerPaquetesConsolidados(Req $req)
    {
        try {
            $user = $req->user();
            $rol = $user->getRoleNames()->first();

            if ($rol == "Cliente") {
                $paquetes = Paquete::where('cliente_id', $user->cliente->id)->where('consolidado_id', null)->where('consolidacion_estado_id',"!=",null)->whereDoesntHave('envio')->get();
                return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
            } else {
                $paquetes = Paquete::where('consolidado_id', null)->where('consolidacion_estado_id',"!=", null)->whereDoesntHave('envio')->get();
                return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function obtenerPaquetesEnviados(Req $req)
    {
        try {
            $user = $req->user();
            $rol = $user->getRoleNames()->first();

            if ($rol == "Cliente") {
                $paquetes = Paquete::join('envios','envios.paquete_id','paquetes.id')->where('cliente_id', $user->cliente->id)->select('paquetes.*')->get();
                //Log::info($paquetes);
                return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
            } else {
                $paquetes = Paquete::join('envios','envios.paquete_id','paquetes.id')->select('paquetes.*')->get();
                return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function obtenerPaquetesAlmacen(Req $req)
    {
        try {
            $user = $req->user();
            $paquetes = Paquete::where('cliente_id', $user->id)->where('almacen_id', $req->almacenId)->where('consolidado_id', null)->where('consolidacion_estado_id', null)->whereDoesntHave('envio')->get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    

    public function obtenerPaquetesAlmacenEditar(Req $req)
    {
        try {
            $user = $req->user();
            //lista en php
            $paquetes = array();

            $paquetesDeconsolidacion = Paquete::where('cliente_id', $user->id)->where('consolidado_id', $req->paqueteId)->get();
            $paquetesDelAlmacen = Paquete::where('cliente_id', $user->id)->where('consolidado_id', null)->where('almacen_id', $req->almacenId)->where('consolidacion_estado_id', null)->whereDoesntHave('envio')->get();

            foreach ($paquetesDeconsolidacion as $paquete) {
                array_push($paquetes, $paquete);
            }

            foreach ($paquetesDelAlmacen as $paquete) {
                array_push($paquetes, $paquete);
            }
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    function guardarimagenPaquete(Req $req)
    {
        try {
            $image = $req->file('imagen');
            $originalName = $image->getClientOriginalName();
            $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
  
            $imageInstance = Image::make($image);
            $newWidth = 1024;
            // Calcular la nueva altura para conservar la relación de aspecto
            $newHeight = intval($newWidth * ($imageInstance->height() / $imageInstance->width()));
            // bajando la resolucion a la imagen
            $imageInstance->resize($newWidth, $newHeight);
            //comprimir imagen
            $encodedImage = (string) $imageInstance->encode('jpg', 40);
            

            //guardar en s3
            $name = $nameWithoutExtension . '.jpg';
            Storage::disk('s3')->put($name, $encodedImage);         
            $imegeUrl = Storage::disk('s3')->url($originalName);

            return response()->json($imegeUrl, 200);

        } catch (\Throwable $th) {
            return response()->json(
                [
                    'mensaje' => $th->getMessage(),
                ],
                500
            );
        }
    }
    
           


    function reconocerPaquete(Req $req)
    {
        try {  

            $imegeUrl = $req->imege_url;
            $client = new Client();

            
            $headers = [
                'Ocp-Apim-Subscription-Key' => config('azure.cognitiveKey'),
                'Content-Type' => 'application/json'
            ];


            $body = '{
             "urlSource": "' . $imegeUrl . '" 
            }';

            $analyzeLabelRequest = new Request('POST', 'https://reconocimientoPaquetes2.cognitiveservices.azure.com/formrecognizer/documentModels/EtiquetasPacketes:analyze?api-version=2023-07-31', $headers, $body);
            $analyzeLabelResponse = $client->sendAsync($analyzeLabelRequest)->wait();

            $headerResponse = $analyzeLabelResponse->getHeaders();

            //obtenemos el link de la solicitud para obtener los resultados 
            $endpointToGetResult = (string) $headerResponse["Operation-Location"][0];

            //esperamos 3 segundos para obtener los resultados
           // Log::info($endpointToGetResult);
            sleep(2);
            $isDone = false;
            while(!$isDone) {
                // Hacer una solicitud para obtener el estado de la operación
                $headers = [
                    'Ocp-Apim-Subscription-Key' => config('azure.cognitiveKey'),
                ];
    
                $requestgetResult = new Request('GET', $endpointToGetResult, $headers);
                $responseResult = $client->sendAsync($requestgetResult)->wait();
                $responseBody = json_decode($responseResult->getBody());

                if ($responseBody->status == 'succeeded') {
                    $isDone = true;
                } else {
                    // Esperar un poco antes de hacer la próxima solicitud
                    sleep(1);
                }
            }
            
            $fields = $responseBody->analyzeResult->documents[0]->fields;

            return response()->json(
                [
                    'nombre' => $fields->nombre->valueString ?? '',
                    'casillero' => $fields->casillero->valueString ?? '',
                    'direccion' => $fields->direccion->valueString ?? '',
                    'numeroRastreo' => $fields->numeroRastreo->valueString ?? '',
                    'photoPath' => $imegeUrl,
                ],
                $responseResult->getStatusCode()
            );
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return response()->json(
                [
                    'mensaje' => $th->getMessage(),
                ],
                500
            );
        } 
    }

    function createPaquete(Req $req)
    {
        try {
            $userActual = $req->user();
            DB::beginTransaction();
            $paquete = new Paquete();
            $paquete->photo_path = $req->photo_path;
            $paquete->codigo_rastreo = $req->codigo_rastreo;
            $paquete->peso = $req->peso;
            $paquete->cliente_id = $req->cliente_id;
            $paquete->almacen_id = $userActual->empleado->almacen_id;
            $paquete->empleado_id = $userActual->id;
            
            $paquete->save();
            DB::commit();
            
            NotificacionPaquete::dispatch($paquete);

            return response()->json(['mensaje' => 'Paquete creado exitosamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }

    

    
}
