<?php

require_once '../../Repositories/UserRepository.php';

UserRepository::updatePassword($_POST['password']);

header('Location: ../../');