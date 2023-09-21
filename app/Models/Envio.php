<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;

    protected $table = 'envios';
    protected $fillable = [
        'fecha_envio',
        'fecha_entrega',
        'costo',
        'paquete_id',
        'metodo_envio_id',
        'envio_estado_id',
    ];
}
