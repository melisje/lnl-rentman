<?php

namespace App\Contracts\Rentman;

interface DataProcessor
{
    /**
     * Process a single page of data.
     */
    public function processPage(string $account, array $items): void;
}
