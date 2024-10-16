<?php

require_once '../../Repositories/LoginRepository.php';

session_start();

if (!empty($_POST['cpfCnpj']) && !empty($_POST['password']))
{
    $_SESSION['cpfCnpj'] = $_POST['cpfCnpj'];
    $response = LoginRepository::login($_POST['cpfCnpj'], $_POST['password']);

    if ($response === null)
    {
        $_SESSION['mensagem'] = [
            'texto' => 'Não foi possível conectar à API. Tente novamente mais tarde.'
        ];
    }
    else if (isset($response['error']) && $response['error'] === 'Bad Request')
    {
        $_SESSION['mensagem'] = [
            'texto' => 'Usuário ou senha incorretos.'
        ];
    }
    else if (isset($response['token']))
    {
        $_SESSION['mensagem'] = [
            'texto' => 'Login realizado com sucesso!'
        ];
        $_SESSION['token'] = $response['token'];
    } 
    else
    {
        $_SESSION['mensagem'] = [
            'texto' => 'Ocorreu um erro inesperado. Tente novamente.'
        ];
    }
}
else
{
    $_SESSION['mensagem'] = [
        'texto' => 'Por favor, preencha todos os campos.'
    ];
}

header('Location: ../../login.php');
exit();
