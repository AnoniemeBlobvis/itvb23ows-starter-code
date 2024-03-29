<?php

namespace App\Game;

class Hand {
    private array $hand;

    private const DEFAULT_STATE = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];

    public function __construct(array $hand = null) {
        if ($hand == null) {
            $this->hand = self::DEFAULT_STATE;
        } else {
            $this->hand = $hand;
        }
    }

    public function getHandArray(): array {
        return $this->hand;
    }

    public function hasPiece(string $piece): bool {
        return isset($this->hand[$piece]);
    }

    public function removePiece(string $piece): void {
        if ($this->hasPiece($piece)) {
            $this->hand[$piece]--;
            if ($this->hand[$piece] == 0) {
                unset($this->hand[$piece]);
            }
        }
    }

    public function getTotalPieces(): int {
        return array_sum($this->hand);
    }

    public function hasQueen(): bool {
        return isset($this->hand["Q"]);
    }
}
