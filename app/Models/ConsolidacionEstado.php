<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsolidacionEstado extends Model
{
    use HasFactory;
    protected $table = 'consolidacion_estado';
    protected $fillable = [
        'name',
    ];

    public function paquete()
    {
        return $this->hasMany('App\Models\Paquete', 'consolidacion_estado_id', 'id');
    }
}
