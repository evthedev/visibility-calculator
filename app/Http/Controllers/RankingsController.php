<?php

namespace App\Http\Controllers;

use App\Services\RankingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use League\Csv\Statement;
use League\Csv\Reader;
use League\Csv\Writer;
use League\Csv\CannotInsertRecord;

class RankingsController extends Controller
{
    private $rankingsService;

    public function __construct()
    {
        $this->rankingsService = new RankingsService();
    }

    public function import(Request $request) {
        
        // Because we're only ever going to deal with one import and one export, the file is fixed here
        $request->file('rankingsFile')->storeAs('import', 'imported-rankings.csv');

        $reader = Reader::createFromPath(storage_path('app/public/import/imported-rankings.csv'), 'r');
        
        // Use the top row as the header titles
        $reader->setHeaderOffset(0);
        $header = $reader->getHeader();

        // Validate header titles
        if (!$this->rankingsService->isValidHeaders($header)) {
            return view('home')->with([
                'error' => "Your file contains invalid headers. They should only be one of the following: 'id', 'date', 'engine', 'searchTerm' or 'ranking'.",
            ]);
        }
        
        $records = collect(iterator_to_array(Statement::create()->process($reader)));
        $output = [];

        foreach ($records as $record) {
            $date = $record['date'];
            $searchTerm = $record['searchTerm'];

            $filtered = $records->where('date', $date)->where('searchTerm', $searchTerm);
            
            $googleRanking = $filtered->where('engine', 'google');
            $yahooRanking = $filtered->where('engine', 'yahoo');
            $bingRanking = $filtered->where('engine', 'bing');

            $googleRankingValue = $googleRanking->count() > 0 ? $googleRanking->first()['ranking'] : 0;
            $yahooRankingValue = $yahooRanking->count() > 0 ? $yahooRanking->first()['ranking'] : 0;
            $bingRankingValue = $bingRanking->count() > 0 ? $bingRanking->first()['ranking'] : 0;

            $visibilityScore = $this->rankingsService->calculateVisibility(
                $googleRankingValue,
                $yahooRankingValue,
                $bingRankingValue
            );

            array_push($output, array(
                'date' => $date,
                'searchTerm' => $searchTerm,
                'visibility' => $visibilityScore
            ));
        }

        try {
            // Clear export file
            file_put_contents(storage_path('app/public/export/exported-rankings.csv'), '');

            // Write to file
            $writer = Writer::createFromPath(storage_path('app/public/export/exported-rankings.csv', 'w+'));
            $writer->insertOne($header);
            $writer->insertAll(collect($output)->unique()->all());

        } catch (CannotInsertRecord $e) {
            $e->getRecords(); //returns [1, 2, 3]
        }

        return view('home')->with([
            'success' => 'Calculation complete!'
        ]);
    }
}
