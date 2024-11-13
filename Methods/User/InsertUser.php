<?php

require_once '../../Repositories/UserRepository.php';

session_start();
unset($_SESSION['name'], $_SESSION['cpfCnpj'], $_SESSION['message']);

if (!empty($_POST['name']) && !empty($_POST['cpfCnpj']) && !empty($_POST['password']))
{
    $response = UserRepository::insert($_POST['name'], $_POST['cpfCnpj'], $_POST['password']);

    if ($response === null)
    {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = 'Não foi possível conectar à API, tente novamente mais tarde.';
        header('Location: ../../SignIn.php');
        exit();
    }
    else if (isset($response['error']))
    {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = $response['error']['message'];
        header('Location: ../../SignIn.php');
        exit();
    }
    else if ($response['httpCode'] >= 200 && $response['httpCode'] < 300)
    {
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = 'Usuário cadastrado com sucesso!';
        header('Location: ../../');
        exit();
    }
    else
    {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = 'Ocorreu um erro inesperado, tente novamente.';
        header('Location: ../../SignIn.php');
        exit();
    }
}
else
{
    $_SESSION['message'] = 'Por favor, preencha todos os campos.';
    header('Location: ../../SignIn.php');
    exit();
}
