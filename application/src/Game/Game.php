<?php

namespace App\Game;

require_once '/var/www/html/vendor/autoload.php';

use App\Database\Database;
use App\Game\Hand;
use App\Game\Board;

class Game{
    private int $id;
    private Database $db;
    private Board $board;
    private Array $hands;
    private int $currentPlayer;
    private ?int $lastMove;

    public function __construct($db, $id = null, $board = null, $hands = null, $currentPlayer = 0, $lastMove = null){
        $this->db = $db;
        $this->id = $id ?? $this->db->insertGame();
        $this->board = $board ?? new Board();
        $this->hands = $hands ?? [0 => new Hand(), 1 => new Hand()];
        $this->currentPlayer = $currentPlayer;
        $this->lastMove = $lastMove;

        if ($id !== null) {
            $this->restoreGame($id);
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getBoard(): Board {
        return $this->board;
    }

    public function getHands(): Array {
        return $this->hands;
    }

    public function getCurrentPlayer(): int {
        return $this->currentPlayer;
    }

    public function getPossiblePositions() {
        return $this->board->calculatePositions($this->board->getState());
    }

    public function getPreviousMoves() {
        return $this->db->getMovesByGameId($this->id);
    }

    public function restoreGame($id): void {
        $game = $this->db->getLastMoveByGameId($id);
        if ($game == null) {
            $this->id = $id;
        } else {
            $this->id = $id;
            $this->setState($game['state']);
            $this->lastMove = $game['previous_id'];
        }
    }

    public function pass(): void {
        $this->lastMove = $this->db->insertMove($this->id, "pass", null, null, $this->previousMove, $this->board->getState());
        $this->currentPlayer = 1 - $this->currentPlayer;
    }

    public function play($piece, $to): void {
        $hand = $this->hands[$this->currentPlayer];

        if (!$hand->hasPiece($piece)) {
            $_SESSION['error'] = "Player does not have tile";
        } elseif ($this->board->isPositionOccupied($to)) {
            $_SESSION['error'] = 'Board position is not empty';
        } elseif ($this->board->hasPieces() && !$this->board->hasNeighbour($to)) {
            $_SESSION['error'] = "Board position has no neighbour";
        } elseif ($hand->getTotalPieces() < 11 && !$this->board->neighboursAreSameColor($this->currentPlayer, $to)) {
            $_SESSION['error'] = "Board position has opposing neighbour";
        } elseif ($hand->getTotalPieces() <= 8 && $hand->hasQueen()) {
            $_SESSION['error'] = 'Must play queen bee';
        } else {
            $this->board->placePiece($piece, $this->currentPlayer, $to);
            $hand->removePiece($piece);
            $this->currentPlayer = 1 - $this->currentPlayer;
            $this->lastMove = $this->db->insertMove($this->id, "play", $piece, $to, $this->lastMove, (string)$this->getState());
        }
    }

    public function move($from, $to): void {
        if (!$this->board->isPositionOccupied($from)) {
            $_SESSION['error'] = 'Board position is empty';
        } elseif ($this->board->getTileOwner($from) != $this->currentPlayer) {
            $_SESSION['error'] = "Tile is not owned by player";
        } elseif ($this->hands[$this->currentPlayer]->hasQueen()) {
            $_SESSION['error'] = "Queen bee is not played";
        } elseif (!$this->board->isMoveValid($from, $to)) {
            $_SESSION['error'] = "Move is invalid";
        } else {
            $this->board->movePiece($from, $to);
            $this->currentPlayer = 1 - $this->currentPlayer;
            $this->lastMove = $this->db->insertMove($this->id, "move", $from, $to, $this->lastMove, (string)$this->getState());
        }
    }

    public function restart(): void {
        $this->board = new Board();
        $this->hands = [0 => new Hand(), 1 => new Hand()];
        $this->currentPlayer = 0;
        $this->lastMove = null;
        $this->id = $this->db->insertGame();
    }

    public function undo(): void {
        $previousMove = $this->db->getMoveById($this->lastMove);
        $this->setState($previousMove['state']);
        $this->lastMove = $previousMove['previous_id'];
    }

    public function getState(): string {
        return serialize([$this->hands, $this->board->getState(), $this->currentPlayer]);
    }

    public function setState($state): void {
        list($a, $b, $c) = unserialize($state);
        $this->hands = $a;
        $this->board->setState($b);
        $this->currentPlayer = $c;
    }

}