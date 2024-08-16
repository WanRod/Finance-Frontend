<?php

require_once '../../Repositories/OutputTypeRepository.php';

if (!empty($_POST['id']))
{
    OutputTypeRepository::delete($_POST['id']);
}

header('Location: ../../');