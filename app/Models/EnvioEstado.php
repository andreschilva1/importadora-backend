<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvioEstado extends Model
{
    use HasFactory;

    protected $table = 'envios_estados';
    protected $fillable = [
        'name',
    ];

    public function envio()
    {
        return $this->hasMany('App\Models\Envio', 'envio_estado_id', 'id');
    }
}
