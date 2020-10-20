<?php

namespace App\Services;
use Exception;

class RankingsService
{
	private const MAXIMUM_SCORE = 31;
    private const COLUMNS = [
        'id',
        'date',
        'engine',
        'searchTerm',
        'ranking',
    ];

	public function calculateVisibility(int $googleScore, int $yahooScore, int $bingScore)
	{
		try {
			return round(((
				(self::MAXIMUM_SCORE - $this->validateScore($googleScore)) * 17 +
				(self::MAXIMUM_SCORE - $this->validateScore($yahooScore)) * 2 +
				(self::MAXIMUM_SCORE - $this->validateScore($bingScore))
			)/600 * 100), 1);
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function validateScore(int $score)
	{
		try {
			if (!isset($score) || $score <= 0 || $score > self::MAXIMUM_SCORE) {
				return self::MAXIMUM_SCORE;
			} else {
				return $score;
			}
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function isValidHeaders($headers)
	{
		foreach($headers as $header) {
			if (!in_array($header, self::COLUMNS)) {
				return false;
			}
		}
		return true;
	}
}