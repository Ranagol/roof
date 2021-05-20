<?php

namespace App\Repository\Crawler;

use App\Crawlers\Crawler;
use App\Models\Ad;
use App\Repository\Interfaces\CrawlerRepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class CrawlerRepository implements CrawlerRepositoryInterface
{
    /**
     * @var Crawler  $crawler
     */
    private $crawler;

    public function __construct()
    {
        $this->crawler = $this->crawler();
    }

    abstract public function crawler(): Crawler;

	/**
	 * Returns the crawled ads.
	 *
	 * @return array
	 */
    public function getAds(): array
    {

        return $this->crawler->getAds();
    }

	/**
	 * Saves the crawled ads to db.
	 *
	 * @param array $ads
	 *
	 * @return void
	 * @throws \Exception
	 */
    public function saveAds(array $ads): void
    {
        $ads = $this->removeJunkAds($ads);
        DB::table('ads')->insert($ads);
        echo 'The crawler was activated, and data was collected. Check the db for the new data.';
    }

    /**
	 * removeJunkAds will:
	 * 1 - remove all ads older than 30 days from from the freshly crawled data
	 * 2 - remove and all ads from the freshly crawled data, that are already in the db. To identify duplicates that
	 * have to be removed, we use the unique original source ad link.
     * And this will happen after the crawling process, but before writing the received data into the
     * db.
     *
     * @param array $ads
     *
     * @return array
     *
     * @throws \Exception
     */
    private function removeJunkAds(array $ads): array
    {
        //get an array of ad ids from the db (we need all existing ads)
        $adLinksFromDb = Ad::all()->pluck('ad_link');

        foreach ($ads as $key => $value ){
            //REMOVING ADS OLDER THAN ONE MONTH FROM THE ARRIVING ADS - we do this first, because it is less costly
            $limitDate = date('c', strtotime('-30 days'));
            $adDate = new \DateTime($value['ad_date']);
            if($adDate < $limitDate){
                unset($ads[$key]);
            }

            //REMOVING DUPLICATES (ads that are already in the db)
            foreach ($adLinksFromDb as $adLinkFromDb) {
                if ($value['ad_link'] === $adLinkFromDb) {
                    //remove all ads from the new superarray, that are already in the db
                    unset($ads[$key]);
                }
            }

        }

        return $ads;
    }
}
