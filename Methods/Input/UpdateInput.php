<?php

require_once '../../Repositories/InputRepository.php';

if (!empty($_POST['id']) && !empty($_POST['description']) && !empty($_POST['value']) && !empty($_POST['date']))
{
    InputRepository::update($_POST['id'], $_POST['description'], $_POST['value'], $_POST['date']);
}

header('Location: ../../index.php');