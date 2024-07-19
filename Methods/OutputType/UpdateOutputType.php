<?php

require_once '../../Repositories/OutputTypeRepository.php';

if (!empty($_POST['id']) && !empty($_POST['description']))
{
    OutputTypeRepository::update($_POST['id'], $_POST['description']);
}

header('Location: ../../index.php');