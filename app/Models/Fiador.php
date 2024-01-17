<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fiador extends Model
{
    use HasFactory;

    protected $table = 'fiador';//llamado de tabla
    public $timestamps = false;
    protected $primaryKey = 'Id_fiador';//se ubica cual es la primary key
    protected $fillable = array(
    'Id_fiador',
    'Id_cliente',
    'Id_colaborador',
    'Nombre',
    'Apellido_pat',
    'Apellido_mat', 
    'No_casa',
    'Calle',
    'Colonia',
    'Estado',
    'No_telefono',
    'INE_frontal_fiador',
    'INE_trasera_fiador'
    );
}
