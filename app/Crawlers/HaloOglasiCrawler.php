<?php

namespace App\Crawlers;

use App\Exceptions\DataExtractionException;
use DOMXPath;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use App\Crawlers\DataExtractors\HaloOglasiDataExtractor;

class HaloOglasiCrawler extends Crawler
{
	/**
	 * @var  $ads
	 */
    private $ads;

    /**
     * Url stores where to do the crawling.
     *
     * @return string
     */
    protected function url(): string
    {
        return 'https://www.halooglasi.com/nekretnine/prodaja-stanova';
    }

	/**
	 * Called AUTOMATICALLY when the crawler has crawled the given url successfully.
	 *
	 * @param UriInterface      $url
	 * @param ResponseInterface $response
	 * @param UriInterface|null $foundOnUrl
	 *
	 * @throws DataExtractionException
	 */
    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null): void
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($response->getBody());
        $xpath = new DOMXPath($doc);
        //https://stackoverflow.com/questions/15602151/fast-way-to-get-specific-data-from-html-string-using-php

        $haloOglasiDataExtractor = new HaloOglasiDataExtractor($xpath);

        $pricesExtracted = $haloOglasiDataExtractor->getPrices();
        $pricesBySurface = $haloOglasiDataExtractor->getPricesBySurface();
        $adDatesExtracted = $haloOglasiDataExtractor->getAdDates();
        $advertisersExtracted = $haloOglasiDataExtractor->getAdvertisers();
        $adAndImageLinks = $haloOglasiDataExtractor->getAdAndImageLink();
        $imageLinksExtracted = $adAndImageLinks[0];
        $adLinksExtracted = $adAndImageLinks[1];
        $surfacesRoomsFloors = $haloOglasiDataExtractor->getSurfacesRoomsFloors();
        $surfacesExtracted = $surfacesRoomsFloors[0];
        $roomsExtracted = $surfacesRoomsFloors[1];
        $floorsExtracted = $surfacesRoomsFloors[2];
        $locationsExtracted = $haloOglasiDataExtractor->getLocations();
        $citiesExtracted = $locationsExtracted[0];
        $locationsInCitiesExtracted = $locationsExtracted[1];

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
            'www.halooglasi.rs'
        );

        $this->ads = $ads;
    }

	/**
	 * Returns the extracted ads, from the crawled data.
	 *
	 * @return array
	 */
    public function getAds(): array
    {
        return $this->ads;
    }
}





