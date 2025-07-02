<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carta extends Model
{
    protected $table    = 'cartas';
    public $timestamps  = false;

    protected $fillable = [
        'numero',
        'numero_combinado',
        'descricao',
    ];
}
