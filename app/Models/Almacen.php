<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    protected $table = 'almacenes';
    protected $fillable = [
        'name',
        'direccion',
        'telefono',
        'pais',
    ];

    public function empleados(){
        return $this->hasMany(Empleado::class,'almacen_id','id');
    }
}
