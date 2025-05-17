<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Renovar Assinatura</title>
    <style>
        body {
            background: #f0f2f5;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
        }

        h2 {
            color: #333;
        }

        form button {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }

        form button:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Renovar Assinatura</h2>
        <p>Renove sua assinatura por mais 30 dias.</p>

        <form method="POST" action="{{ route('subscription.renew') }}">
            @csrf
            <button type="submit">Renovar por 30 dias</button>
        </form>
    </div>
</body>
</html>
