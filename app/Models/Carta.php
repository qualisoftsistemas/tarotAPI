<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carta extends Model
{
    protected $table = 'cartas';
    protected $fillable = [
        'numero',
        'numero_combinado',
        'descricao',
    ];
}
