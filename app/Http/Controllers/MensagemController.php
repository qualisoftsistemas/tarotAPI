<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MensagemController extends Controller
{
    public function index(Request $request)
    {
        $data_inicial = $request->input('data_inicial');
        $data_final = $request->input('data_final');

        if (!$data_inicial || !$data_final) {
            return response()->json([
                'status' => [
                    'code'      => 400,
                    'timestamp' => $this->TimestampBr(),
                    'message'   => 'Enviar valores de data_inicial e data_final'
                ],
                'mensagens' => null
            ], 400);
        }

        $data_inicial = Carbon::createFromFormat('Y-m-d', $data_inicial)->startOfDay();
        $data_final = Carbon::createFromFormat('Y-m-d', $data_final)->endOfDay();

        if (!$data_inicial->isValid() || !$data_final->isValid()) {
            return response()->json([
                'status' => [
                    'code'      => 400,
                    'timestamp' => $this->TimestampBr(),
                    'message'   => 'Valores de data_inicial e data_final em formato errado'
                ],
                'mensagens' => null
            ], 400);
        }

        if ($data_inicial->greaterThan($data_final)) {
            return response()->json([
                'status' => [
                    'code'      => 400,
                    'timestamp' => $this->TimestampBr(),
                    'message'   => 'Valores de data_inicial é maior que data_final'
                ],
                'mensagens' => null
            ], 400);
        }

        $mensagens = Mensagem::whereBetween('created_at', [$data_inicial, $data_final])
            ->get();

        return response()->json([
            'status' => [
                'code'      => 200,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Mensagens encontradas com sucesso'
            ],
            'mensagens' => $mensagens
        ], 200);
    }

    public function store(Request $request)
    {
        $mensagem = null;
        $nome = $request->input('nome');

        if (!$nome) {
            return response()->json([
                'status' => [
                    'code'      => 400,
                    'timestamp' => $this->TimestampBr(),
                    'message'   => 'É obrigatório enviar o nome'
                ],
                'mensagem' => $mensagem
            ], 400);
        }

        $email = $request->input('email');

        if (!$email) {
            return response()->json([
                'status' => [
                    'code'      => 400,
                    'timestamp' => $this->TimestampBr(),
                    'message'   => 'É obrigatório enviar o email'
                ],
                'mensagem' => $mensagem
            ], 400);
        }

        $idade = $request->input('idade');
        $sexo = $request->input('sexo');
        $mensagem = $request->input('mensagem');

        $mensagem = Mensagem::create([
            'nome'      => $nome,
            'email'     => $email,
            'idade'     => $idade,
            'sexo'      => $sexo,
            'mensagem'  => $mensagem
        ]);

        return response()->json([
            'status' => [
                'code'      => 200,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Mensagem salva com sucesso'
            ],
            'mensagem' => $mensagem
        ], 200);
    }
}
