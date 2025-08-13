<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table    = 'mensagens';

    protected $fillable = [
        'nome',
        'email',
        'idade',
        'sexo',
        'mensagem'
    ];

    protected $hidden = [
        'updated_at'
    ];
}
