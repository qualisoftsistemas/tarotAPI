<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            color: #333;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #ddd;
            max-width: 600px;
            margin: auto;
        }
        h2 {
            margin-top: 0;
            color: #444;
        }
        .info {
            margin-bottom: 15px;
        }
        .info strong {
            display: inline-block;
            width: 100px;
        }
        .mensagem {
            background: #f4f4f4;
            border-left: 4px solid #007BFF;
            padding: 10px;
            white-space: pre-line;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📩 Novo contato recebido</h2>

        <div class="info">
            <strong>Nome:</strong> {{ $nome }}
        </div>
        <div class="info">
            <strong>Email:</strong> {{ $email }}
        </div>

        @if(!empty($idade))
        <div class="info">
            <strong>Idade:</strong> {{ $idade }}
        </div>
        @endif

        @if(!empty($sexo))
        <div class="info">
            <strong>Sexo:</strong> {{ $sexo }}
        </div>
        @endif

        <h3>Mensagem:</h3>
        <div class="mensagem">
            {{ $mensagem ?? '---' }}
        </div>
    </div>
</body>
</html>
