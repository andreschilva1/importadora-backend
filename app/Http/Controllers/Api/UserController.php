<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createEmployee(Request $request)
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
            $user->assignRole('Empleado');

            $empleado = new Empleado();
            $empleado->user_id = $user->id;
            $empleado->almacen_id = $request->almacenId;
            $empleado->save();

            DB::commit();
            return response()->json(['mensaje' => 'Empleado creado exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function getEmployees()
    {
        try {

            /* $empleados = User::role('Empleado')->get(); */

            $roles = [
                'Encargado de Almacen',
                'Encargado de Envio',
                'Encargado de compra'
            ];
            $empleados = User::whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })->get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $empleados], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function editEmployee(Request $request)
    {
        try {
            $user = User::find($request->id);
            $rol = $user->getRoleNames()->first();
            return response()->json([
                'mensaje' => 'Consulta exitosa',
                'status' => 'success',
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $rol,
                'celular' => $user->celular,
                'photo_path' => $user->profile_photo_path,
                'casillero' => ($rol == "Cliente") ?  $user->cliente->numero_casillero : '',
                'almacen' => ($rol == "Empleado") ?  $user->empleado->almacen->name : '',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function deleteEmployee(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::findOrFail($request->id);
            $user->delete();
            DB::commit();
            return response()->json(['mensaje' => 'Empleado eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }
}
