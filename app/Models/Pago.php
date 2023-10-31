<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $table = 'pago';
    protected $fillable = [
        'total',
        'envio_id',
    ];

    public function envio()
    {
        return $this->belongsTo('App\Models\Envio', 'envio_id', 'id');
    }
}
