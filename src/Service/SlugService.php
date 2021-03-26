<?php


namespace App\Service;


use Monolog\Logger;
use Psr\Log\LoggerInterface;

class SlugService
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        //var_dump("contructeur slug");
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        $this->addLog($text);
        return $text;
    }

    private function addLog(string $text)
    {
        $this->logger->info("On a slugifié " . $text);
    }
}
