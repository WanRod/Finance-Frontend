<?php

require_once '../../Repositories/OutputRepository.php';

if (!empty($_POST['output-type-id']) && !empty($_POST['description']) && !empty($_POST['value']) && !empty($_POST['date']))
{
    OutputRepository::insert($_POST['output-type-id'], $_POST['description'], $_POST['value'], $_POST['date']);
}

header('Location: ../../');