<?php

namespace Tests\Unit;

use App\Services\RankingsService;
use PHPUnit\Framework\TestCase;

class RankingsServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    
    public function testCalculateVisibility()
    {
        $rankingsService = new RankingsService();
        $this->assertEquals(99.3, $rankingsService->calculateVisibility(1, 2, 3));
    }

    public function testValidateScore()
    {
        $rankingsService = new RankingsService();
        
        // validate that a value between 0 and 31 returns the same
        $this->assertEquals(1, $rankingsService->validateScore(1));

        // validate that a value below 0 and more than 31 returns 0
        $this->assertEquals(31, $rankingsService->validateScore(-1));
        $this->assertEquals(31, $rankingsService->validateScore(32));        
    }

    public function testIsValidHeaders()
    {
        $rankingsService = new RankingsService();
        
        // return false for invalid header
        $this->assertFalse($rankingsService->isValidHeaders(['invalid header']));
        // return true for valid header
        $this->assertTrue($rankingsService->isValidHeaders(['searchTerm']));
    }

}
