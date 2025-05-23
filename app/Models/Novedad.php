<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novedad extends Model
{
    use HasFactory;

    protected $table = 'novedad';

    protected $fillable = [
        'titulo',
        'detalle',
        'nombreRuta',
        'url',
        'pie',
        'usuario',
        'empresa_id',
    ];
}