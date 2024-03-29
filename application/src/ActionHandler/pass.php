<?php

namespace App\ActionHandler;

require_once '/var/www/html/vendor/autoload.php';
session_start();

use App\Database\Database;
use App\Game\Game;

$gameID = $_SESSION['Game']->getId();

$game = new Game(new Database(), $gameID);
$game->pass();
$_SESSION['Game'] = $game;

header('Location: /index.php');
