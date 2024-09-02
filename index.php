<?php
if (!isset($_SESSION))
{
    session_start();
}

if (!isset($_SESSION['token']))
{
    header('Location: Login.php');
    exit();
}

require_once 'Repositories/InputRepository.php';
require_once 'Repositories/OutputRepository.php';
require_once 'Repositories/OutputTypeRepository.php';
require_once 'Repositories/DashboardRepository.php';

date_default_timezone_set("America/Fortaleza");

if (!isset($_SESSION['menu'])) 
{
    $_SESSION['menu'] = "output";
}

if (isset($_POST['toggle'])) 
{
    switch ($_SESSION['menu'])
    {
        case "output":
            $_SESSION['menu'] = "input";
            break;
        
        case "input":
            $_SESSION['menu'] = "outputType";
            break;

        case "outputType":
            $_SESSION['menu'] = "output";
            break;

        default:
            $_SESSION['menu'] = "output";
            break;
    }
}
else if (isset($_POST['dashboard']))
{
    $_SESSION['menu'] = "dashboard";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/36e17004f7.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style/style.css">
    <title>Finanças</title>
</head>

<body>
    <header>
        <nav class="row">
            <div class="d-flex align-items-center col">
                <div class="border border-2 rounded-circle dollar-circle d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-dollar-sign dollar-sign"></i>
                </div>
                <h1 class="mt-1 mx-2">Finanças</h1>
            </div>
            <div class="align-content-center text-end col">
                <button class="btn btn-profile"><i class="fa-solid fa-circle-user me-1"></i>Perfil</button>
                <button class="btn btn-logout" data-bs-toggle="modal" data-bs-target="#logout-modal"><i class="fa-solid fa-right-from-bracket me-1"></i>Sair</button>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <form method="post">
                <button class="btn btn-outline-primary rounded-pill my-4 menu" type="submit" name="toggle"><i class="fa-solid fa-rotate-right"></i> Trocar de página</button>
                <button class="btn btn-outline-info rounded-pill my-4 menu" type="submit" name="dashboard"><i class="fa-solid fa-chart-pie"></i> Dashboard</button>
            </form>

            <?php
            switch ($_SESSION['menu'])
            {
                case "output":
                    include_once 'Components/OutputForm.php';
                    include_once 'Components/OutputTable.php';                    
                    break;
                
                case "input":
                    include_once 'Components/InputForm.php';
                    include_once 'Components/InputTable.php';
                    break;

                case "outputType":
                    include_once 'Components/OutputTypeForm.php';
                    include_once 'Components/OutputTypeTable.php';
                    break;

                case "dashboard":
                    include_once 'Components/Dashboard.php';
                    break;

                default:
                    include_once 'Components/OutputForm.php';
                    include_once 'Components/OutputTable.php';  
                    break;
            }
            ?>
        </div>
    </main>

    <?php
        include_once 'Components/Modals/LogoutModal.html';
    ?>

    <script>
        $(document).ready(function() 
        {
            $('#confirm-logout').on('click', function() 
            {
                $.ajax({
                    url: 'Login.php',
                    type: 'POST',
                    data: 
                    { 
                        logout: true,
                    },
                    success: function(response)
                    {
                        location.replace(location.href);
                    },
                    error: function(xhr, status, error) 
                    {

                    }
                });
            });
        });
    </script>
</body>

</html>