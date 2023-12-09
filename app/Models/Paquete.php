<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    use HasFactory;

    protected $table = 'paquetes';
    protected $fillable = [
        'codigo_rastreo',
        'peso',
        'photo_path',
        'cliente_id',
        'almacen_id',
        'empleado_id',
        'consolidacion_estado_id',
        'consolidado_id'
    ];

    public function envio()
    {
        return $this->hasOne('App\Models\Envio', 'paquete_id', 'id');
    }

    public function paquete()
    {
        return $this->hasMany('App\Models\Paquete', 'consolidado_id', 'id');
    }
}
