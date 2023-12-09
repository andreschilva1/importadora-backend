<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'paises';
    protected $fillable = [
        'name',
    ];

    public function almacenes()
    {
        return $this->hasMany(Almacen::class, 'pais_id', 'id');
    }

    public function metodosEnvios()
    {
        return $this->hasMany(MetodoEnvio::class, 'pais_id', 'id');
    }
}
