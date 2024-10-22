<?php

require_once '../../Repositories/LoginRepository.php';
session_start();

unset($_SESSION['cpfCnpj']);

if (!empty($_POST['cpfCnpj']) && !empty($_POST['password']))
{
    $response = LoginRepository::login($_POST['cpfCnpj'], $_POST['password']);

    if ($response === null)
    {
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = 'Não foi possível conectar à API, tente novamente mais tarde.';
    }
    else if (isset($response['error']))
    {
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = $response['error']['message'];
    }
    else if (isset($response['token']))
    {
        $_SESSION['message'] = 'Login realizado com sucesso!';
        $_SESSION['token'] = $response['token'];
    } 
    else
    {
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = 'Ocorreu um erro inesperado. Tente novamente.';
    }
} else {
    $_SESSION['message'] = 'Por favor, preencha todos os campos.';
}

header('Location: ../../login.php');
exit();
