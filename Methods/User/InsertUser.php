<?php

require_once '../../Repositories/UserRepository.php';

if (!empty($_POST['name']) && !empty($_POST['cpfCnpj']) && !empty($_POST['password']))
{
    UserRepository::insert($_POST['name'], $_POST['cpfCnpj'], $_POST['password']);
}

header('Location: ../../');