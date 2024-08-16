<?php

require_once '../../Repositories/OutputTypeRepository.php';

if (!empty($_POST['description']))
{
    OutputTypeRepository::insert($_POST['description']);
}

header('Location: ../../');