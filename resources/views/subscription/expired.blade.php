<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Assinatura Expirada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
        }

        h1 {
            color: #dc3545;
            margin-bottom: 20px;
        }

        p {
            color: #6c757d;
            margin-bottom: 30px;
        }

        input, button {
            padding: 10px;
            width: 100%;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Assinatura expirada</h1>
        <p>Digite a senha de liberação temporária:</p>
        
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('subscription.unlock') }}">
            @csrf
            <input type="password" name="password" placeholder="Senha de liberação">
            <button type="submit">Liberar acesso</button>
        </form>
    </div>
</body>
</html>
