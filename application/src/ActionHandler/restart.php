<?php

namespace App\ActionHandler;

require_once '/var/www/html/vendor/autoload.php';
session_start();

use App\Database\Database;
use App\Game\Game;

unset($_SESSION['Game']);

header('Location: /index.php');
