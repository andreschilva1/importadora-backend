<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $fillable = [
        'numero_casillero',
        'token_android',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
