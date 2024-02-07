<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';//llamado de tabla
    public $timestamps = false;
    protected $primaryKey = 'Id_cliente';//se ubica cual es la primary key
    protected $fillable = array(
    'Id_cliente',
    'Id_colaborador',
    'Nombre',
    'Apellido_paterno',
    'Apellido_materno',
    'Email', 
    'Numero_celular',
    'Ciudad',
    'Estado',
    'Pais', 
    'Ref1_nombre',
    'Ref2_nombre',
    'Ref1_celular',
    'Ref2_celular',
    'Ref1_parentesco',
    'Ref2_parentesco',
    'Motivo_visita', 
    'Lugar_motivo_visita', 
    'Foto_cliente', 
    'INE_frente', 
    'INE_reverso',
    'Estatus_cliente'

    );
}
