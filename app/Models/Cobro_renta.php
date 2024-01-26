<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobro_renta extends Model
{
    use HasFactory;

    protected $table = 'cobro_renta';//llamado de tabla
    public $timestamps = false;
    protected $primaryKey = 'Id_cobro_renta';//se ubica cual es la primary key
    protected $fillable = array(
        'Id_cobro_renta',
        'Id_reservacion',
        'Id_lugares_reservados',
        'Id_colaborador',
        'Cobro_persona_extra',
        'Periodo_total',
        'Estatus_cobro',
        'Tiempo_rebasado',
        'Deposito_garantia',
        'Monto_total',
        'Saldo'
    );
}
