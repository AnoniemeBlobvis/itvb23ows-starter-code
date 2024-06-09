<?php

namespace App\Game;

class Board {
    private array $state;
    public const OFFSETS = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

    public function __construct(array $state = []) {
        $this->state = $state;
    }

    public function getState(): array {
        return $this->state;
    }

    public function setState(array $state): void
    {
        $this->state = $state;
    }

    public function placePiece($piece, $player, $position): void {
        $this->state[$position] = [[$player, $piece]];
    }

    public function movePiece($from, $to): void {
        $tile = array_pop($this->state[$from]);
        $this->state[$to] = $tile;
    }

    public function isPositionOccupied($position): bool {
        return isset($this->state[$position]);
    }

    public function hasPieces(): bool {
        return count($this->state) > 0;
    }

//    public function isNeighbour($pos, $b): bool {
//        $a = explode(',', $pos);
//        $b = explode(',', $b);
//        if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) {
//            return true;
//        }
//        if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1 || $a[0] + $a[1] == $b[0] + $b[1]) {
//            return true;
//        }
//        return false;
//    }

    public function isNeighbour($pos, $b): bool {
        $a = explode(',', $pos);
        $b = explode(',', $b);

        // Check if the second element exists before trying to access it
        if (!isset($a[1]) || !isset($b[1])) {
            // Handle the case where $a[1] or $b[1] doesn't exist
            return false;
        }

        if ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) {
            return true;
        }
        if ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1 || $a[0] + $a[1] == $b[0] + $b[1]) {
            return true;
        }
        return false;
    }

    public function hasNeighbour($pos): bool {
        foreach (array_keys($this->state) as $b) {
            if ($this->isNeighbour($pos, $b)) {
                return true;
            }
        }
        return false;
    }

    public function neighboursAreSameColor($player, $pos): bool {
        foreach ($this->state as $b => $st) {
            if (!$st) {
                continue;
            }
            $c = $st[count($st) - 1][0];
            if ($c != $player && $this->isNeighbour($pos, $b)) {
                return false;
            }
        }
        return true;
    }

    public function getTileOwner($pos) {
        return $this->state[$pos][count($this->state[$pos]) - 1][0];
    }

    public function isMoveValid($from, $to): bool {
        $tile = array_pop($this->state[$from]);
        if (!$this->hasNeighbour($to)) {
            return false;
        } else {
            if ($tile === null || !isset($tile[1])) {
                return false;
            }
            $all = array_keys($this->state);
            $queue = [array_shift($all)];
            while ($queue) {
                $next = explode(',', array_shift($queue));
                foreach (self::OFFSETS as $offset) {
                    $p = $next[0] + $offset[0];
                    $q = $next[1] + $offset[1];
                    $pos = "$p,$q";
                    if (in_array($pos, $all)) {
                        $queue[] = $pos;
                        $all = array_diff($all, [$pos]);
                    }
                }
            }

            if ($all) {
                return false;
            } elseif ($from == $to) {
                return false;
            } elseif (isset($this->state[$to]) && $tile[1] != "B") {
                return false;
            } elseif ($tile[1] == "Q" || $tile[1] == "B") {
                if (!$this->slide($from, $to)) {
                    return false;
                }
            }
        }

        // Restore the popped tile in case of error
        if (isset($_SESSION['error'])) {
            $this->state[$from][] = $tile;
            return false;
        }

        // No errors, proceed with move
        $this->state[$to][] = $tile;
        return true; // Move is valid
    }

    public function slide($from, $to): bool {
        if (!$this->hasNeighBour($to)) {
            return false;
        }
        if (!$this->isNeighbour($from, $to)) {
            return false;
        }
        $b = explode(',', $to);
        $common = [];
        foreach (self::OFFSETS as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            if ($this->isNeighbour($from, $p.",".$q)) {
                $common[] = $p.",".$q;
            }
        }
        if (!isset($this->state[$common[0]]) || !isset($this->state[$common[1]]) ||
            !isset($this->state[$from]) || !isset($this->state[$to])) {
            return false;
        }
        if (!$this->state[$common[0]] && !$this->state[$common[1]] && !$this->state[$from] && !$this->state[$to]) {
            return false;
        }
        return min($this->len($this->state[$common[0]]), $this->len($this->state[$common[1]]))
            <= max($this->len($this->state[$from]), $this->len($this->state[$to]));
    }

    public function len($tile) {
        return $tile ? count($tile) : 0;
    }

    public function calculatePositions($board) {
        $to = [];
        foreach (self::OFFSETS as $pq) {
            foreach (array_keys($board) as $pos) {
                $pq2 = explode(',', $pos);
                $to[] = ($pq[0] + intval($pq2[0])).','.((isset($pq2[1])) ? ($pq[1] + intval($pq2[1])) : 0);
            }
        }
        $to = array_unique($to);
        if (!count($to)) {
            $to[] = '0,0';
        }

        return $to;
    }
}


