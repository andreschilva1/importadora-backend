<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $table = 'empleados';
    protected $fillable = [
        'user_id',
        'almacen_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function almacen(){
        return $this->belongsTo(Almacen::class,'almacen_id','id');
    }

}
