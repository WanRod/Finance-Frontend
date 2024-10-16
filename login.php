<?php
session_start();

if (isset($_POST['logout'])) {
    unset($_SESSION['token']);
}

if (isset($_SESSION['token'])) {
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
                    <input type="text" class="form-control" id="cpfCnpj" name="cpfCnpj" value="<?php echo isset($_SESSION['cpfCnpj']) ? htmlspecialchars($_SESSION['cpfCnpj']) : ''; ?>" required>
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

    <!-- Modal para mensagens -->
    <?php if (isset($_SESSION['mensagem'])): ?>
    <div class="modal fade" id="mensagemModal" tabindex="-1" role="dialog" aria-labelledby="mensagemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <?php echo $_SESSION['mensagem']['texto']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function()
        {
            $('#mensagemModal').modal('show');

            setTimeout(function()
            {
                $('#mensagemModal').modal('hide');
            }, 6000);

            <?php unset($_SESSION['mensagem']); ?>
        });
    </script>

    <?php endif; ?>

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
