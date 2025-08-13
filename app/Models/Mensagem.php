<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table    = 'mensagens';
    public $timestamps  = false;

    protected $fillable = [
        'nome',
        'email',
        'idade',
        'sexo',
        'mensagem'
    ];
}
