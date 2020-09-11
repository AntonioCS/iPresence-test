<?php

namespace App\Service\Quotes;

interface DataProviderInterface
{
    public function fetch(string $key, int $limit) : array;
}
