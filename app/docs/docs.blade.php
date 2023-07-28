<!DOCTYPE html>
<html>
<head>
    <title>Workout Routes Documentation</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Workout Routes Documentation</h1>
        <div id="routes" class="container"></div>
        <h3>A resposta será mostrada aqui</h3>
        <div id="output">Clique em "Testar" em alguma rota para ver o resultado.</div>
    </div>

    <style>
        text {
            padding: 10px;
            opacity: 0.3;
            position: fixed;
            bottom: 10px;
            z-index: 1;
            user-select: none;
        }

        .btn-primary{
            display: block;
            margin: 15px;
        }

        #output{
            font-size: 15px;
            font-weight: bold;
            padding-bottom: 80px;
            z-index: 10;
            position: relative;
        }
    </style>

    <script>
        // Função para exibir as rotas na tela
        function showRoutes(routes) {
            var routesDiv = document.getElementById('routes');

            Object.keys(routes).forEach(function(route) {
                var methods = routes[route];

                var h2 = document.createElement('h2');
                h2.textContent = route;
                routesDiv.appendChild(h2);

                var ul = document.createElement('ul');
                ul.classList.add('list-group');

                Object.keys(methods).forEach(function(method) {
                    var details = methods[method];

                    var li = document.createElement('li');
                    li.classList.add('list-group-item');

                    // Adicionar classe CSS para o método HTTP
                    li.classList.add('method-' + method.toLowerCase());

                    var typeMethods = document.createElement('div');
                    typeMethods.classList.add('badge', 'bg-primary');
                    typeMethods.textContent = method;

                    li.appendChild(typeMethods);

                    var routeElement = document.createElement('span');
                    routeElement.classList.add('summary');
                    routeElement.innerHTML = "<strong>" + route + "</strong>";
                    li.appendChild(routeElement);

                    var testButton = document.createElement('button');
                    testButton.classList.add('btn', 'btn-primary', 'btn-sm');
                    testButton.textContent = 'Testar';

                    testButton.addEventListener('click', function() {
                        testRoute(route);
                    });

                    li.appendChild(testButton);
                    ul.appendChild(li);
                });

                routesDiv.appendChild(ul);
            });
        }

        // Função para exibir a resposta da rota no output
        function showResponse(response, route) {
            var outputElement = document.getElementById('output');
            var json = JSON.stringify(response, null, 2);
            outputElement.innerHTML = `<h4>Você clicou em testar a rota: <span class="badge">${route}</span></h4> <br><h2>Reponse</h2><br> ${JSON.stringify(response, null, 2)}`;
        }

        // Função para fazer a requisição na rota e exibir a resposta
        function testRoute(route) {
            var outputElement = document.getElementById('output');
            fetch(route)
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    showResponse(data, route);
                })
                .catch(function(error) {
                    outputElement.textContent = "Não é uma resposta válida, a rota tem que enviar algum JSON válido."
                    console.log(error);
                });
        }

        // Fazer a requisição GET para a rota /workoutjson
        fetch('/workout_json')
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                showRoutes(data.paths);
            })
            .catch(function(error) {
                console.log(error);
            });
    </script>
</body>
</html>
