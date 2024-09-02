<?php

require_once '../../Repositories/OutputRepository.php';

if (!empty($_POST['id']))
{
    OutputRepository::delete($_POST['id']);
}

header('Location: ../../');