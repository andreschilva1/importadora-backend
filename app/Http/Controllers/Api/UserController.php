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
    public function createEmployee(Request $request){
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

    public function getEmployees(){
        try {
            $empleados = User::role('Empleado')->get();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $empleados], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function editEmployee(Request $request){
        try {
            $employee = User::where("id", $request->id)->first();
            return response()->json(['mensaje' => 'Consulta exitosa', 'data' => $employee], 200);
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()], 500);
        }
    }

    public function deleteEmployee(Request $request){
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
