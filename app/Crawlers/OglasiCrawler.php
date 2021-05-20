<?php

namespace App\Crawlers;

use App\Crawlers\DataExtractors\OglasiDataExtractor;
use DOMXPath;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class OglasiCrawler extends Crawler
{
    private $ads;

    protected function url(): string
    {
        return 'https://www.oglasi.rs/oglasi/nekretnine/prodaja/stanova';
    }

	/**
	 * Called when the crawler has crawled the given url successfully.
	 *
	 * @param UriInterface      $url
	 * @param ResponseInterface $response
	 * @param UriInterface|null $foundOnUrl
	 *
	 * @throws \App\Exceptions\DataExtractionException
	 */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($response->getBody());
        $xpath = new DOMXPath($doc);
        //https://stackoverflow.com/questions/15602151/fast-way-to-get-specific-data-from-html-string-using-php

        $oglasiDataExtractor = new OglasiDataExtractor($xpath);

        $pricesExtracted = $oglasiDataExtractor->getPrices();
        $adLinksExtracted = $oglasiDataExtractor->getAdLinks();
        $imageLinksExtracted = $oglasiDataExtractor->getImageLinks();
        $adDatesExtracted = $oglasiDataExtractor->getAdDate();
        $advertisersExtracted = $oglasiDataExtractor->getAdvertiser();
        $locations = $oglasiDataExtractor->getLocation();
        $citiesExtracted = $locations[0];
        $locationsInCitiesExtracted = $locations[1];
        $surfacesFloorsRooms = $oglasiDataExtractor->getSurfaceFloorRoomsAge();
        $roomsExtracted = $surfacesFloorsRooms[0];
        $surfacesExtracted = $surfacesFloorsRooms[1];
        $floorsExtracted = $surfacesFloorsRooms[2];

        //the priceBySurface will be calculated right before writing data into the db.
        $pricesBySurface = $this->countPriceBySurface($pricesExtracted, $surfacesExtracted);

        //create an array, that will contain all data in subarrays
        $ads = $this->collectAllAdData(
            $pricesExtracted,
            $pricesBySurface,
            $surfacesExtracted,
            $roomsExtracted,
            $floorsExtracted,
            $citiesExtracted,
            $locationsInCitiesExtracted,
            $adLinksExtracted,
            $imageLinksExtracted,
            $advertisersExtracted,
            $adDatesExtracted,
            'www.oglasi.rs'
        );

        $this->ads = $ads;
    }

	/**
	 * Returns the ads.
	 *
	 * @return array
	 */
    public function getAds(): array
    {
        return $this->ads;
    }

	/**
	 * In case of Oglasi.rs crawling there is no data about the price-by-surface. This is something that we need to count,
	 * and this is happening in this function.
	 *
	 * @param array $pricesExtracted
	 * @param array $surfacesExtracted
	 *
	 * @return array
	 */
    private function countPriceBySurface(array $pricesExtracted, array $surfacesExtracted): array
    {
        $pricesBySurface = [];
        foreach ($pricesExtracted as $key => $value){
            $priceBySurface = $value / $surfacesExtracted[$key];
            $pricesBySurface[] = $priceBySurface;
        }

        return $pricesBySurface;
    }
}
