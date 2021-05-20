<?php

namespace App\Repository\Crawler;

use App\Crawlers\Crawler;
use App\Crawlers\OglasiCrawler;

class OglasiCrawlerRepository extends CrawlerRepository
{
    public function crawler(): Crawler
    {
        return new OglasiCrawler();
    }
}
