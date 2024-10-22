<?php
session_start();

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
    <script src="https://kit.fontawesome.com/36e17004f7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/style.css">
    <title>Finanças</title>
</head>

<body class="d-flex flex-column vh-100">
    <header>
        <nav class="row">
            <div class="col d-flex align-items-center header-logo">
                <div class="border border-2 rounded-circle dollar-circle d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-dollar-sign dollar-sign"></i>
                </div>
                <h1 class="mt-1 mx-2">Finanças</h1>
            </div>
            <div class="col align-content-center text-end ">   
                <form action="Login.php">
                    <button type="submit" class="btn btn-return"><i class="fa-solid fa-right-from-bracket me-1"></i>Retornar</button>
                </form>
            </div>
        </nav>
    </header>

    <main class="d-flex flex-grow-1 justify-content-center align-items-center">
        <div class="container container-sign-in">
            <div class="text-center">
                <h3>Cadastrar usuário</h3>
            </div>

            <form action="Methods/User/InsertUser.php" method="POST">
                <div class="mb-2">
                    <label for="name" class="fw-bold">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" maxlength="50" required>
                </div>

                <div class="mb-2">
                    <label for="cpfCnpj" class="fw-bold">CPF / CNPJ</label>
                    <input type="text" class="form-control" id="cpfCnpj" name="cpfCnpj" maxlength="18" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="fw-bold">Senha</label>
                    <input type="password" class="form-control" id="password" name="password" maxlength="50" required>
                </div>

                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn save submit-login"><i class="fa-solid fa-check"></i> Salvar</button>
                    <button type="reset" class="btn clear"><i class="fa-solid fa-trash-can"></i> Limpar</button>
                </div>
            </form>
        </div>
    </main>

    <script>
    $(document).ready(function() {
        var cpfCnpj = $('#cpfCnpj');

        cpfCnpj.on('input', function() {
            var valor = cpfCnpj.val().replace(/\D/g, '');

            if (valor.length <= 11) {
                valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            } else {
                valor = valor.substring(0, 14);
                valor = valor.replace(/^(\d{2})(\d)/, '$1.$2');
                valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');
                valor = valor.replace(/(\d{4})(\d)/, '$1-$2');
            }

            cpfCnpj.val(valor);
        });
    });
    </script>
</body>

</html>