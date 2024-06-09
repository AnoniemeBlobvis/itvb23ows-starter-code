<?php

use PHPUnit\Framework\TestCase;
use App\Database\Database;
use App\Game\Game;
use App\Game\Board;
use App\Game\Hand;

class GameTest extends TestCase
{
    private $dbMock;
    private $boardMock;
    private $handMock;

    protected function setUp(): void
    {
        $this->dbMock = $this->createMock(Database::class);
        $this->boardMock = $this->createMock(Board::class);
        $this->handMock = $this->createMock(Hand::class);
    }

    public function testGetLegalMovePositions()
    {
        $this->boardMock->method('getState')->willReturn([
            'A1' => ['owner' => 0],
            'B2' => ['owner' => 0],
            'C3' => ['owner' => 1]
        ]);
        $this->boardMock->method('isPositionOccupied')->will($this->returnValueMap([
            ['A1', true],
            ['B2', true],
            ['C3', true],
            ['D4', false]
        ]));
        $this->boardMock->method('getTileOwner')->will($this->returnValueMap([
            ['A1', 0],
            ['B2', 0],
            ['C3', 1]
        ]));
        $this->boardMock->method('isMoveValid')->will($this->returnValueMap([
            ['A1', 'D4', true],
            ['B2', 'D4', true],
            ['C3', 'D4', false]
        ]));
        $this->boardMock->method('calculatePositions')->willReturn(['D4']);

        $game = new Game($this->dbMock, 1, $this->boardMock, [0 => $this->handMock, 1 => $this->handMock]);

        $legalMovePositions = $game->getLegalMovePositions();

        $this->assertArrayHasKey('A1', $legalMovePositions);
        $this->assertEquals(['D4'], $legalMovePositions['A1']);
        $this->assertArrayHasKey('B2', $legalMovePositions);
        $this->assertEquals(['D4'], $legalMovePositions['B2']);
        $this->assertArrayNotHasKey('C3', $legalMovePositions);
    }

    public function testGetCurrentPlayerPositions()
    {
        $this->boardMock->method('getState')->willReturn([
            'A1' => ['owner' => 0],
            'B2' => ['owner' => 0],
            'C3' => ['owner' => 1],
            'D4' => ['owner' => 0]
        ]);
        $this->boardMock->method('getTileOwner')->will($this->returnValueMap([
            ['A1', 0],
            ['B2', 0],
            ['C3', 1],
            ['D4', 0]
        ]));

        $game = new Game($this->dbMock, 1, $this->boardMock, [0 => $this->handMock, 1 => $this->handMock]);

        $currentPlayerPositions = $game->getCurrentPlayerPositions(0);

        $this->assertEquals(['A1', 'B2', 'D4'], $currentPlayerPositions);
    }

    public function testGetLegalPlayPositions()
    {
        $this->boardMock->method('hasPieces')->willReturn(true);
        $this->boardMock->method('getState')->willReturn([
            'A1' => ['owner' => 0],
            'B2' => ['owner' => 1]
        ]);
        $this->boardMock->method('isPositionOccupied')->will($this->returnValueMap([
            ['A3', false],
            ['C1', false],
            ['B2', true]
        ]));
        $this->boardMock->method('hasNeighbour')->will($this->returnValueMap([
            ['A3', true],
            ['C1', true],
            ['B2', true]
        ]));
        $this->boardMock->method('calculatePositions')->willReturn(['A3', 'C1']);

        $this->handMock->method('getHandArray')->willReturn([
            'piece1' => 1,
            'piece2' => 1
        ]);

        $game = new Game($this->dbMock, 1, $this->boardMock, [0 => $this->handMock, 1 => $this->handMock]);

        $legalPlayPositions = $game->getLegalPlayPositions();

        $this->assertArrayHasKey('piece1', $legalPlayPositions);
        $this->assertEquals(['A3', 'C1'], $legalPlayPositions['piece1']);
        $this->assertArrayHasKey('piece2', $legalPlayPositions);
        $this->assertEquals(['A3', 'C1'], $legalPlayPositions['piece2']);
    }

    public function testGetLegalPlayPositionsNoPieces()
    {
        $this->boardMock->method('hasPieces')->willReturn(false);
        $this->boardMock->method('calculatePositions')->willReturn(['A3', 'C1']);

        $this->handMock->method('getHandArray')->willReturn([
            'piece1' => 1,
            'piece2' => 1
        ]);

        $game = new Game($this->dbMock, 1, $this->boardMock, [0 => $this->handMock, 1 => $this->handMock]);

        $legalPlayPositions = $game->getLegalPlayPositions();

        $this->assertArrayHasKey('piece1', $legalPlayPositions);
        $this->assertEquals(['A3', 'C1'], $legalPlayPositions['piece1']);
        $this->assertArrayHasKey('piece2', $legalPlayPositions);
        $this->assertEquals(['A3', 'C1'], $legalPlayPositions['piece2']);
    }
}
