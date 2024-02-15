<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobro_desperfectos extends Model
{
    use HasFactory;


    protected $table = 'cobro_desperfectos';//llamado de tabla
    public $timestamps = false;
    protected $primaryKey = 'Id_cobro_desperfecto';//se ubica cual es la primary key
    protected $fillable = array(
    'Id_cobro_desperfecto',
    'Id_reservacion',
    'Monto_total_desperfecto',
    'Saldo_desperfecto',
    'Olor_cigarro',
    'Deteccion_plagas', 
    'Muebles_dañados',
    'Ropa_cama_dañada',
    'Limpieza_profunda',
    'Vidrios_rotos', 
    'Otro_desperfecto',
    'Monto_otro_desperfecto'

    );
}
