<?php

namespace App\ActionHandler;

require_once '/var/www/html/vendor/autoload.php';
session_start();

use App\Database\Database;
use App\Game\Game;

$tile = $_POST['piece'];
$to = $_POST['to'];

$gameID = $_SESSION['Game']->getId();

$game = new Game(new Database(), $gameID);
$game->play($tile, $to);
$_SESSION['Game'] = $game;

header('Location: /index.php');
