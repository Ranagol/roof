<?php

namespace App\Repository\Crawler;

use App\Crawlers\Crawler;
use App\Crawlers\HaloOglasiCrawler;

class HaloOglasiCrawlerRepository extends CrawlerRepository
{
    public function crawler(): Crawler
    {
        return new HaloOglasiCrawler();
    }
}
