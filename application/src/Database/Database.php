<?php
namespace App\Database;

use Dotenv\Dotenv;
use mysqli;

class Database {
    private mysqli $connection;

    public function __construct(){
        $dotenv = Dotenv::createImmutable('/var/www/html/');
        $dotenv->load();

        $this->connection = new mysqli(
            'p:' . $_ENV['MYSQL_HOST'],
            $_ENV['MYSQL_USER'],
            $_ENV['MYSQL_PASSWORD'],
            $_ENV['MYSQL_DATABASE']
        );
    }

    public function getMovesByGameId($game_id) {
        $stmt = $this->connection->prepare('SELECT * FROM moves WHERE game_id = ?');
        $stmt->bind_param('i', $game_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insertGame(): int {
        $stmt = $this->connection->prepare('INSERT INTO games VALUES ()');
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function insertMove(int $gameId, string $type, ?string $from, ?string $to, ?int $previousId, string $state): int {
        $stmt = $this->connection->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('isssis', $gameId, $type, $from, $to, $previousId, $state);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function getMoveById(int $id) {
        $stmt = $this->connection->prepare('SELECT * FROM moves WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    public function getLastMoveByGameId(int $gameId) {
        $stmt = $this->connection->prepare('SELECT * FROM moves WHERE game_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->bind_param('i', $gameId);
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }
}