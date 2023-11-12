<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rastreo extends Model
{
    use HasFactory;

    protected $table = 'rastreos';
    protected $fillable = [
        'name',
        'codigo_rastreo',
    ];

    public function envio()
    {
        return $this->belongsTo(Envio::class);
    }
}
