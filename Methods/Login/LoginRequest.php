<?php

require_once '../../Repositories/LoginRepository.php';

if (!empty($_POST['cpfCnpj']) && !empty($_POST['password']))
{
    LoginRepository::login($_POST['cpfCnpj'], $_POST['password']);
}

header('Location: ../../');