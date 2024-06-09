<?php

use PHPUnit\Framework\TestCase;
use App\Game\Board;

class BoardTest extends TestCase
{
    public function testIsMoveValid()
    {
        $initialState = [
            '0,0' => [[0, 'Q']],
            '0,1' => [[0, 'A']],
            '1,0' => [[0, 'B']],
            '1,1' => [[0, 'G']]
        ];
        $board = new Board($initialState);

        $this->assertTrue($board->isMoveValid('0,0', '1,1'));
        $this->assertFalse($board->isMoveValid('0,0', '2,2'));
    }

    public function testIsMoveValidWithoutNeighbours()
    {
        $initialState = [
            '0,0' => [[0, 'Q']]
        ];
        $board = new Board($initialState);

        $this->assertFalse($board->isMoveValid('0,0', '2,2'));
    }

    public function testSlide()
    {
        $initialState = [
            '0,0' => [[0, 'Q']],
            '0,1' => [[0, 'A']],
            '1,0' => [[0, 'B']],
            '1,1' => [[0, 'G']]
        ];
        $board = new Board($initialState);

        $this->assertTrue($board->slide('0,0', '0,1'));
        $this->assertFalse($board->slide('0,0', '2,2'));
    }

    public function testCalculatePositions()
    {
        $initialState = [
            '0,0' => [[0, 'Q']]
        ];
        $board = new Board($initialState);
        $expectedPositions = [
            '-1,0', '-1,1', '0,1', '1,-1', '1,0', '1,1'
        ];

        $calculatedPositions = $board->calculatePositions($initialState);

        foreach ($expectedPositions as $position) {
            $this->assertContains($position, $calculatedPositions);
        }

        $this->assertContains('0,0', $board->calculatePositions([]));
    }

    public function testCalculatePositionsForEmptyBoard()
    {
        $board = new Board();
        $expectedPositions = ['0,0'];

        $this->assertEquals($expectedPositions, $board->calculatePositions([]));
    }
}
