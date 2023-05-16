<!DOCTYPE html>
<html>
<head>
    <title>Bem-vindo ao Workut</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .text {
            text-align: center;
        }

        .text h1 {
            font-size: 6rem;
            margin: 0;
            color: #ffcc00;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .text p {
            font-size: 1.5rem;
            margin: 0;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .text a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ffcc00;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text">
            <h1>Bem-vindo ao {{ $logo }}</h1>
            <p>{{ $descricao }}</p>
        </div>
    </div>
</body>
</html>
