<?php

require_once '../../Repositories/InputTypeRepository.php';

if (!empty($_POST['id']))
{
    $response = InputTypeRepository::delete($_POST['id']);

    if ($response == null)
    {
        $_SESSION['message'] = 'Não foi possível conectar à API, tente novamente mais tarde.';
    } 
    else if (isset($response['error']))
    {
        $errorMessages = $response['error']['message'];

        if (is_array($errorMessages))
        {
            $errorMessages = array_map(function($error)
            {
                return $error['error'];
            }, $errorMessages);
            $errorMessage = implode(" | ", $errorMessages);
        }
        else
        {
            $errorMessage = $errorMessages;
        }

        $_SESSION['message'] = $errorMessage;
    }
    else if ($response['httpCode'] < 200 || $response['httpCode'] > 299)
    {
        $_SESSION['message'] = 'Ocorreu um erro inesperado, tente novamente.';
    }
}
else
{
    $_SESSION['message'] = 'Por favor, preencha todos os campos.';
}

header('Location: ../../');
exit();