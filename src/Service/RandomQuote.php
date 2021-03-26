<?php


namespace App\Service;


class RandomQuote
{
    public function getDalyQuote()
    {
        $quotes = ['quote1', 'quote2'];
        $random = rand(0, 1);
        return $quotes[$random];
    }
}
