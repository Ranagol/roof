<?php


namespace App\Repository\Interfaces;

use App\Crawlers\Crawler;

interface CrawlerRepositoryInterface
{
    /**
     *
     * Which crawler should the class use.
     *
     * @return Crawler
     */
    public function crawler(): Crawler;

    /**
     *
     * Returns all ads from the supplier.
     *
     * @return array
     */
    public function getAds(): array;

    /**
     *
     * Cleans ads data and then saves it into the db.
     *
     * @param array $ads
     *
     * @return void
     * @throws \Exception
     */
    public function saveAds(array $ads): void;
}
