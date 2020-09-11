<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{

    public function testApiShout()
    {
        $client = static::createClient();

        $client->request('GET', '/api/shout/steve-jobs?limit=9');
        $expected_result = "[\"YOUR TIME IS LIMITED, SO DON\u2019T WASTE IT LIVING SOMEONE ELSE\u2019S LIFE!\",\"THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO.\"]";
        $result = $client->getResponse()->getContent();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expected_result, $result);
    }
}
