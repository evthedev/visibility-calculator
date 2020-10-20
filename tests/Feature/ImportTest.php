<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportTest extends TestCase
{
    use WithoutMiddleware;
    
    public function testImportDataSet1()
    {
        $response = $this->json('POST', '/', [
            'rankingsFile' => new \Illuminate\Http\UploadedFile(base_path('tests/mock/import/imported-rankings-1.csv'), true)
        ]);
        $response->assertStatus(200);

        // Compare output file with expected results in /tests/mock/export directory
        $outputResultsReader = Reader::createFromPath(storage_path('app/public/export/exported-rankings.csv'), 'r');
        $outputResultsReader->setHeaderOffset(0);
        $outputRecords = iterator_to_array(Statement::create()->process($outputResultsReader));
        
        $expectedResultsReader = Reader::createFromPath(base_path('tests/mock/export/exported-rankings-1.csv'), 'r');
        $expectedResultsReader->setHeaderOffset(0);
        $expectedRecords = iterator_to_array(Statement::create()->process($expectedResultsReader));
            
        $this->assertEquals($outputRecords, $expectedRecords);
    }

    public function testImportDataSet2()
    {
        $response = $this->json('POST', '/', [
            'rankingsFile' => new \Illuminate\Http\UploadedFile(base_path('tests/mock/import/imported-rankings-2.csv'), true)
        ]);
        $response->assertStatus(200);

        // Compare output file with expected results in /tests/mock/export directory
        $outputResultsReader = Reader::createFromPath(storage_path('app/public/export/exported-rankings.csv'), 'r');
        $outputResultsReader->setHeaderOffset(0);
        $outputRecords = iterator_to_array(Statement::create()->process($outputResultsReader));
        
        $expectedResultsReader = Reader::createFromPath(base_path('tests/mock/export/exported-rankings-2.csv'), 'r');
        $expectedResultsReader->setHeaderOffset(0);
        $expectedRecords = iterator_to_array(Statement::create()->process($expectedResultsReader));
            
        $this->assertEquals($outputRecords, $expectedRecords);
    }
}