<?php

require_once '../../Repositories/OutputRepository.php';

if (!empty($_POST['id']) && !empty($_POST['outputTypeId']) && !empty($_POST['description']) && !empty($_POST['value']) && !empty($_POST['date']))
{
    OutputRepository::update($_POST['id'], $_POST['outputTypeId'], $_POST['description'], $_POST['value'], $_POST['date']);
}

header('Location: ../../');