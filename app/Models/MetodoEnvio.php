<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoEnvio extends Model
{
    use HasFactory;

    protected $table = 'metodos_envios';
    protected $fillable = [
        'transportista',
        'metodo',
        'costo_kg',
        'pais_id',
    ];

    public function envio()
    {
        return $this->hasMany('App\Models\Envio', 'metodo_envio_id', 'id');
    }

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id', 'id');
    }
}
