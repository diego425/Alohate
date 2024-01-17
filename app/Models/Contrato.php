<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';//llamado de tabla
    public $timestamps = false;
    protected $primaryKey = 'Id_contrato';//se ubica cual es la primary key
    protected $fillable = array(
    'Id_contrato',
    'Id_fiador',
    'Id_colaborador',
    'Id_reservacion',
    'Fecha_inicio',
    'Fecha_termino', 
    'Foto_contrato',
    'Tipo_contrato',
    );
}
