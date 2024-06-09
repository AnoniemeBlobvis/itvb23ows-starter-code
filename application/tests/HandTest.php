<?php

use PHPUnit\Framework\TestCase;
use App\Game\Hand;

class HandTest extends TestCase
{
    // Tests for (bug) Issue #1

    public function testGetHandArrayReturnsDefaultState()
    {
        $hand = new Hand();
        $expected = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $this->assertEquals($expected, $hand->getHandArray());
    }

    public function testGetHandArrayReturnsCustomState()
    {
        $customHand = ["Q" => 1, "B" => 1, "S" => 1, "A" => 1, "G" => 1];
        $hand = new Hand($customHand);
        $this->assertEquals($customHand, $hand->getHandArray());
    }

    public function testGetHandArrayAfterRemovingPiece()
    {
        $hand = new Hand();
        $hand->removePiece("Q");
        $expected = ["B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $this->assertEquals($expected, $hand->getHandArray());
    }

    public function testGetHandArrayAfterRemovingMultiplePieces()
    {
        $hand = new Hand();
        $hand->removePiece("A");
        $hand->removePiece("A");
        $hand->removePiece("A");
        $expected = ["Q" => 1, "B" => 2, "S" => 2, "G" => 3];
        $this->assertEquals($expected, $hand->getHandArray());
    }

    public function testGetHandArrayAfterRemovingAllPieces()
    {
        $customHand = ["Q" => 1];
        $hand = new Hand($customHand);
        $hand->removePiece("Q");
        $expected = [];
        $this->assertEquals($expected, $hand->getHandArray());
    }

    public function testGetHandArrayWithEmptyInitialHand()
    {
        $hand = new Hand([]);
        $expected = [];
        $this->assertEquals($expected, $hand->getHandArray());
    }
}
