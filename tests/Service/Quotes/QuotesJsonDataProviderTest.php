<?php

namespace App\Tests\Service\Quotes;

use App\Service\Quotes\QuotesJsonDataProvider;
use PHPUnit\Framework\TestCase;

class QuotesJsonDataProviderTest extends TestCase
{

    private QuotesJsonDataProvider $testObj;

    public function setUp() : void
    {
        $testQuotesFile = __DIR__ . '/quotes.json';
        $this->testObj = new QuotesJsonDataProvider($testQuotesFile, 5, null);
    }

    public function testFetch()
    {
        $result = $this->testObj->fetch("t1", 2);

        $this->assertTrue(count($result) == 1);
        $this->assertEquals("TEST 1", $result[0]);
    }


    public function testFetchNoData()
    {
        $result = $this->testObj->fetch("no_author", 2);
        $this->assertTrue(empty($result));
    }
}
