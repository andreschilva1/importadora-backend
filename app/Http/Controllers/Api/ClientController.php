<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function createClient(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->profile_photo_path = null;
            $user->celular = $request->celular;
            $user->save();
            $user->assignRole('Cliente');

            // Obtener el inicial del nombre
            $inicialNombre = substr($request->name, 0, 1);

            // Obtener el ID del usuario creado
            $userId = strval($user->id);

            // Obtener los últimos 4 caracteres (dígitos) del número de celular
            $ultimos4DigitosCelular = substr($request->celular, -4);

            $client = new Cliente();
            $client->numero_casillero = $inicialNombre . $userId . $ultimos4DigitosCelular;
            $client->token_android = null;
            $client->user_id = $user->id;
            $client->save();
            DB::commit();
            return response()->json(['mensaje' => 'Cliente creado exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function editClient(Request $request)
    {
        try {
            $cliente = User::join('clientes', 'users.id', 'clientes.user_id')->where("users.id", $request->id)->first();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $cliente], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function getClientByCasillero(Request $request)
    {
        try {
            $cliente = Cliente::where("numero_casillero", $request->numero_casillero)->first();
            if (!$cliente) {
                return response()->json(['mensaje' => 'No se encontró el cliente'], 404);
            }

            return response()->json([
                'id' => $cliente->id,
                'nombre' => $cliente->user->name,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
}
