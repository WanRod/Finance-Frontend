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
    <link rel="stylesheet" href="style/style.css">
    <title>Finanças</title>
</head>

<body style="background: url('style/img/login-background.png') no-repeat center center fixed; background-size: cover;">
    <main class="d-flex align-items-center vh-100">
        <div class="container" style="background-color: #FFFFFF; border-radius: 15px; padding: 30px; max-width: 350px; max-height: 400px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);">
            <div class="text-center mb-5">
                <h2 class="fw-bold"><u>Login</u></h2>
            </div>

            <form action="Methods/Login/LoginRequest.php" method="POST">
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

            <div class="row">
                <div class="col">
                    <a href="#" style="font-size: 12px;">Não tem uma conta?</a>
                </div>
                <div class="col text-end">
                    <a href="#" style="font-size: 12px;">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
    </main>
</body>

</html>