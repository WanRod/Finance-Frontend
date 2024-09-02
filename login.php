<?php
if (!isset($_SESSION))
{
    session_start();
}

if (isset($_POST['logout']))
{
    unset($_SESSION['token']);
}

if (isset($_SESSION['token'])) 
{
    header('Location: /finance-frontend/');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <link rel="stylesheet" href="style/style.css">
    <title>Finanças</title>
</head>

<body class="body-login">
    <main class="d-flex align-items-center vh-100">
        <div class="container container-login">
            <div class="text-center mb-5">
                <h2 class="fw-bold"><u>Login</u></h2>
            </div>

            <form action="Methods/Login/LoginRequest.php" method="POST" id="loginForm">
                <div class="mb-4">
                    <label for="cpfCnpj" class="fw-bold">CPF / CNPJ</label>
                    <input type="text" class="form-control" id="cpfCnpj" name="cpfCnpj" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="fw-bold">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-primary rounded-pill submit-login">Entrar</button>
                </div>
            </form>

            <div class="row links-login">
                <div class="col">
                    <a href="#">Não tem uma conta?</a>
                </div>
                <div class="col text-end">
                    <a href="#">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
    </main>

    <script>
    $(document).ready(function()
    {
        var cpfCnpjField = $('#cpfCnpj');

        function applyMask()
        {
            var valor = cpfCnpjField.val().replace(/\D/g, ''); 
            if (valor.length + 1 > 11)
            {
                cpfCnpjField.mask('00.000.000/0000-00', {reverse: false});
            }
            else 
            {
                cpfCnpjField.mask('000.000.000-00', {reverse: false});
            }
        }

        cpfCnpjField.on('input', function() 
        {
            applyMask();
        });

        // Remove caracteres especiais antes de enviar o formulário
        $('#loginForm').on('submit', function() 
        {
            cpfCnpjField.val(cpfCnpjField.val().replace(/\D/g, ''));
        });
    });
    </script>

</body>

</html>
