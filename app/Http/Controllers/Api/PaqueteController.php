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
                $paquetes = Paquete::where('cliente_id', $user->id)->where('consolidado_id', null)->get();
                return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
            } else {
                $paquetes = Paquete::where('consolidado_id', null)->get();
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
            $paquetes = Paquete::where('cliente_id', $user->id)->where('almacen_id', $req->almacenId)->where('consolidado_id', null)->where('consolidacion_estado_id', 2)->get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function obtenerPaquetesConsolidacion(Req $req)
    {
        try {
            $paquetes = Paquete::where('consolidado_id', $req->paqueteId)->get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function obtenerPaquetesAlmacenEditar(Req $req)
    {
        try {
            $user = $req->user();
            $paquetes = Paquete::where('cliente_id', $user->id)->where('almacen_id', $req->almacenId)->where('consolidacion_estado_id', 2)->get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $paquetes], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    function reconocerPaquete(Req $req)
    {
        try {
            $image = $req->file('imagen');
            $originalName = $image->getClientOriginalName();
            $fileNameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
           
            Log::info($fileNameWithoutExtension);


            //comprimir imagen
            $imageInstance = Image::make($image);
            $newWidth = 1024;
            // Calcular la nueva altura para conservar la relación de aspecto
            $newHeight = intval($newWidth * ($imageInstance->height() / $imageInstance->width()));
            Log::info($newHeight);
            $imageInstance->resize($newWidth, $newHeight);
            $encodedImage = (string) $imageInstance->encode('jpg', 40);
            

            //guardar en s3
            $name = $fileNameWithoutExtension . '.jpg';
            //Log::info($name);
            Storage::disk('s3')->put($name, $encodedImage);         
            $imegeUrl = Storage::disk('s3')->url($originalName);


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
            Log::info($endpointToGetResult);
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

                //obtener el codigo estado de la solicitud
                $statusCode = $responseResult->getStatusCode();
                // Verificar si la operación está completa
                Log::info($statusCode);
                Log::info($responseBody->status);
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
                    'nombre' => $fields->nombre->valueString,
                    'casillero' => $fields->casillero->valueString,
                    'direccion' => $fields->direccion->valueString,
                    'numeroRastreo' => $fields->numeroRastreo->valueString,
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
            
            $paquete->consolidacion_estado_id = 2;
            $paquete->save();
            DB::commit();
            
            NotificacionPaquete::dispatch($paquete);

            return response()->json(['mensaje' => 'Paquete creado exitosamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }

    function registrarConsolidacion(Req $req)
    {
        try {
            $userActual = $req->user();
            DB::beginTransaction();
            $paquete = Paquete::findOrFail($req->paqueteId);
            $paquete->peso = $req->peso;
            $paquete->photo_path = $req->photo_path;
            $paquete->codigo_rastreo = $req->codigo_rastreo;
            $paquete->empleado_id = $userActual->id;
            $paquete->consolidacion_estado_id = 2;
            $paquete->save();
            DB::commit();


            return response()->json(['mensaje' => 'Paquete creado exitosamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }

    function createConsolidacion(Req $req)
    {
        try {
            $userActual = $req->user();
            DB::beginTransaction();
            $paquete = new Paquete();
            $paquete->peso = $req->peso;
            $paquete->cliente_id = $userActual->id;
            $paquete->almacen_id = $req->almacenId;
            $paquete->empleado_id = null;
            $paquete->consolidacion_estado_id = 1;
            $paquete->save();

            $paquetesIds = $req->lista_ids_paquetes;

            $paquetes = Paquete::whereIn('id', $paquetesIds)->get();
            foreach ($paquetes as $paq) {
                $paq->consolidado_id = $paquete->id;
                $paq->save();
            }

            $paquete->save();
            DB::commit();

            return response()->json(['mensaje' => 'Consolidación creado exitosamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }

    function editConsolidacion(Req $req)
    {
        try {
            DB::beginTransaction();
            $paquete = Paquete::findOrFail($req->paqueteId);
            $paquete->peso = $req->peso;
            $paquete->save();

            $paquetes = Paquete::where('consolidado_id', $req->paqueteId)->get();
            foreach ($paquetes as $paq) {
                $paq->consolidado_id =  null;
                $paq->save();
            }

            $paquetesIds = $req->lista_ids_paquetes;

            $paquetesEditar = Paquete::whereIn('id', $paquetesIds)->get();
            foreach ($paquetesEditar as $paq) {
                $paq->consolidado_id = $paquete->id;
                $paq->save();
            }

            $paquete->save();
            DB::commit();

            return response()->json(['mensaje' => 'Consolidación editado exitosamente'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['mensaje' => $th->getMessage()], 500);
        }
    }
}
