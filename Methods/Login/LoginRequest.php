<?php

require_once '../../Repositories/LoginRepository.php';
session_start();

unset($_SESSION['cpfCnpj']);

if (!empty($_POST['cpfCnpj']) && !empty($_POST['password']))
{
    $response = LoginRepository::login($_POST['cpfCnpj'], $_POST['password']);
    
    if ($response == null)
    {
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = 'Não foi possível conectar à API, tente novamente mais tarde.';
    } 
    else if (isset($response['error']))
    {
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = $response['error']['message'];
    } 
    else if (isset($response['body']['token']))
    {
        $_SESSION['token'] = $response['body']['token'];
    } 
    else
    {
        $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
        $_SESSION['message'] = 'Ocorreu um erro inesperado, tente novamente.';
    }
} 
else
{
    $_SESSION['message'] = 'Por favor, preencha todos os campos.';
}

header('Location: ../../Login.php');
exit();
