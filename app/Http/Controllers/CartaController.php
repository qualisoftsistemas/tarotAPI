<?php

namespace App\Http\Controllers;

use App\Models\Carta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CartaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartas = $this->todasAsCartas();

        return response()->json([
            'status' => [
                'code'      => 200,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Registros encontrados'
            ],
            'cartas' => $cartas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero'                      => 'required|integer',
            'numero_combinado'            => 'required|integer',
            'descricao'                   => 'required|max:65000',
        ], [
            'numero.required'             => 'Inserir o numero é obrigatório',
            'numero.integer'              => 'Numero deve ser inteiro',
            'numero_combinado.required'   => 'Inserir o numero_combinado é obrigatório',
            'numero_combinado.integer'    => 'Numero combinado deve ser inteiro',
            'descricao.required'          => 'Inserir a descrição é obrigatório',
            'descricao.max'               => 'A descrição não pode ultrapassar 65000 caracteres',
        ]);

        $cartas = Carta::create($request->all());

        Cache::forget($this->cacheKey('todas_cartas'));

        return response()->json([
            'status' => [
                'code'      => 201,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Carta criada com sucesso!'
            ],
            'cartas' => $cartas
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $carta = Carta::find($id);

        if (!$carta) {
            return response()->json([
                'status' => [
                    'code'      => 404,
                    'timestamp' => $this->TimestampBR(),
                    'message'   => 'Registro não encontrado'
                ],
                'carta' => null
            ], 404);
        }

        return response()->json([
            'status' => [
                'code'      => 200,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Registro encontrado'
            ],
            'carta' => $carta
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $carta = Carta::find($id);

        if (!$carta) {
            return response()->json([
                'status' => [
                    'code'      => 404,
                    'timestamp' => $this->TimestampBR(),
                    'message'   => 'Registro não encontrado'
                ],
                'carta' => null
            ], 404);
        }

        $request->validate([
            'numero'                      => 'required|integer',
            'numero_combinado'            => 'required|integer',
            'descricao'                   => 'required|max:65000',
        ], [
            'numero.required'             => 'Inserir o numero é obrigatório',
            'numero.integer'              => 'Numero deve ser inteiro',
            'numero_combinado.required'   => 'Inserir o numero_combinado é obrigatório',
            'numero_combinado.integer'    => 'Numero combinado deve ser inteiro',
            'descricao.required'          => 'Inserir a descrição é obrigatório',
            'descricao.max'               => 'A descrição não pode ultrapassar 65000 caracteres',
        ]);

        $carta->update($request->all());

        Cache::forget($this->cacheKey('todas_cartas'));

        return response()->json([
            'status' => [
                'code'      => 200,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Carta atualizada com sucesso!'
            ],
            'carta' => $carta
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->json([
            'status' => [
                'code'      => 403,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Não é possível deletar cartas no momento'
            ],
        ], 403);

        $carta = Carta::find($id);

        if (!$carta) {
            return response()->json([
                'status' => [
                    'code'      => 404,
                    'timestamp' => $this->TimestampBR(),
                ],
                'carta' => null
            ], 404);
        }

        $carta->delete();

        Cache::forget($this->cacheKey('todas_cartas'));

        return response()->json([
            'status' => [
                'code'      => 200,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Carta deletada com sucesso!'
            ],
            'carta' => $carta
        ], 200);
    }

    public function sortear_cartas()
    {
        $allCartas = $this->todasAsCartas();

        $puras = $allCartas
            ->filter(fn($carta) => $carta->numero === $carta->numero_combinado);

        $sorteadas = $puras->shuffle()->take(3)->values();

        $posicoes  = ['passado', 'presente', 'futuro'];
        $resultado = $sorteadas->map(fn($carta, $i) => [
            'posicao' => $posicoes[$i],
            'id'         => $carta->id,
            'numero'     => $carta->numero,
            'descricao'  => $carta->descricao,
            'imagem_url' => asset("storage/img/{$carta->numero}.jpg"),
        ]);

        return response()->json([
            'status' => [
                'code'      => 200,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Cartas sorteadas com sucesso',
            ],
            'tiragem' => $resultado,
        ], 200);
    }

    public function analisar_cartas(string $carta1, string $carta2, string $carta3)
    {
        $allCartas = $this->todasAsCartas();

        $passado  = $allCartas->firstWhere('id', (int)$carta1);
        $presente = $allCartas->firstWhere('id', (int)$carta2);
        $futuro   = $allCartas->firstWhere('id', (int)$carta3);

        if (! $passado || ! $presente || ! $futuro) {
            return response()->json([
                'status' => [
                    'code'      => 404,
                    'message'   => 'Uma ou mais cartas não foram encontradas.',
                    'timestamp' => $this->TimestampBr(),
                ]
            ], 404);
        }

        $comboPassado = $allCartas->first(
            fn($carta) =>
            $carta->numero                  === $presente->numero &&
                $carta->numero_combinado    === $passado->numero_combinado
        );

        $comboFuturo = $allCartas->first(
            fn($carta) =>
            $carta->numero                  === $presente->numero &&
                $carta->numero_combinado    === $futuro->numero_combinado
        );

        $analise = [
            'passado_presente' => [
                'id'        => $comboPassado?->id,
                'descricao' => $comboPassado?->descricao,
            ],
            'presente' => [
                'id'        => $presente->id,
                'descricao' => $presente->descricao,
            ],
            'presente_futuro' => [
                'id'        => $comboFuturo?->id,
                'descricao' => $comboFuturo?->descricao,
            ],
        ];

        return response()->json([
            'status' => [
                'code'      => 200,
                'timestamp' => $this->TimestampBr(),
                'message'   => 'Análise gerada',
            ],
            'analise' => $analise
        ], 200);
    }
}
