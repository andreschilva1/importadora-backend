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

    public function paquete()
    {
        return $this->belongsTo('App\Models\Paquete', 'paquete_id', 'id');
    }

    public function envioEstado()
    {
        return $this->belongsTo('App\Models\EnvioEstado', 'envio_estado_id', 'id');
    }

    public function metodoEnvio()
    {
        return $this->belongsTo('App\Models\MetodoEnvio', 'metodo_envio_id', 'id');
    }

    public function pago()
    {
        return $this->hasOne('App\Models\Pago', 'envio_id', 'id');
    }

    public function seguimiento()
    {
        return $this->hasMany(Seguimiento::class, 'envio_id', 'id');
    }
}
