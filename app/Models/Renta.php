<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cobro_renta';//llamado de tabla
    public $timestamps = false;
    protected $primaryKey = 'Id_cobro_renta';//se ubica cual es la primary key
}
