<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    use HasFactory;

    //llamado de tabla
    protected $table = 'bancos';
    public $timestamps = false;
    //se ubica cual es la primary key
    protected $primaryKey = 'Id_Banco';
}
