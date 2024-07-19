<?php

require_once '../../Repositories/InputRepository.php';

if (!empty($_POST['id']))
{
    InputRepository::delete($_POST['id']);
}

header('Location: ../../index.php');