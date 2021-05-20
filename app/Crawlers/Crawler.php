<?php


namespace App\Crawlers;

use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

abstract class Crawler extends CrawlObserver
{
    public function __construct()
    {
        \Spatie\Crawler\Crawler::create()
            ->setConcurrency(1)
            ->setMaximumDepth(1)
            ->acceptNofollowLinks()
            ->setDelayBetweenRequests(150)
            ->setCrawlObserver($this)
            ->setCurrentCrawlLimit(1)
            ->setTotalCrawlLimit(1)
            ->setParseableMimeTypes(['text/html', 'text/plain'])
            ->startCrawling($this->url());
    }

    abstract protected function url(): string;

    /**
     * Called when the crawler will crawl the url.
     *
     * @param UriInterface $url
     *
     * @return string
     */
    public function willCrawl(UriInterface $url): string
    {
        return 'Crawling started.' . '<br>';
    }

    /**
     * Called when the crawler has crawled the given url successfully.
     *
     * @param UriInterface $url
     * @param ResponseInterface $response
     * @param UriInterface|null $foundOnUrl
     */
    abstract public function crawled(
        UriInterface $url,
        ResponseInterface $response,
        ?UriInterface $foundOnUrl = null
    ): void;

    abstract public function getAds(): array;

    /**
     * Called when the crawler has a problem crawling the given url.
     *
     * @param UriInterface $url
     * @param RequestException $requestException
     * @param UriInterface|null $foundOnUrl
     */
    public function crawlFailed( UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null): string
    {
        throw $requestException;
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling(): string
    {
        return 'Crawling finished.' . '<br>';
    }

	/**
	 * collectAllAdData collects all the separated, not united ad data, and unites them into ads.
	 * When we extract the needed data from the data the was crawled, first we extract all the ad prices,
	 * then we extract all the surfaces, then we extract all the rooms... etc. In case if we crawled
	 * 20 ads on the oglasi.rs, now we would have 20 prices in a separate array, 20 surfaces in a separate
	 * array, 20 rooms in a separate array... etc. We have to unite the separate prices, surfaces, rooms
	 * into complete ads. This is happening here. The logic is simple: the first price has to united with
	 * the first surface and the first room... etc, the second price has to be united with the second
	 * surface and second room...etc. We know that we have the same amount of prices, surface, rooms...
	 * Now, we want to achieve this with only one foreach loop, and not to loop separatelly for price, then
	 * for surface, then for rooms. See the comment beside the foreach loop.
	 * The future $ad has string keyes, that are exactly matching the column names in the ads table,
	 * in the db. This is important, because we will use this, to insert all data from ads at once into
	 * the db, and not record by record.
	 *
	 * @param array  $pricesExtracted
	 * @param array  $pricesBySurface
	 * @param array  $surfacesExtracted
	 * @param array  $roomsExtracted
	 * @param array  $floorsExtracted
	 * @param array  $citiesExtracted
	 * @param array  $locationsInCitiesExtracted
	 * @param array  $adLinksExtracted
	 * @param array  $imageLinksExtracted
	 * @param array  $advertisersExtracted
	 * @param array  $adDatesExtracted
	 * @param string $source
	 *
	 * @return array
	 */
    protected function collectAllAdData(
        array $pricesExtracted,
        array $pricesBySurface,
        array $surfacesExtracted,
        array $roomsExtracted,
        array $floorsExtracted,
        array $citiesExtracted,
        array $locationsInCitiesExtracted,
        array $adLinksExtracted,
        array $imageLinksExtracted,
        array $advertisersExtracted,
        array $adDatesExtracted,
        string $source
    ): array {
        $ads = [];
        foreach ($pricesExtracted as $key => $value) {//here we have key and price as a value...
            $ad = [
                'price' => $value,//..so we use this value here...
                'price_by_surface' => $pricesBySurface[$key],//...but we use the key for every next array
                'surface' => $surfacesExtracted[$key],
                'number_of_rooms' => $roomsExtracted[$key],
                'floor' => $floorsExtracted[$key],
                'city' => $citiesExtracted[$key],
                'location_in_city' => $locationsInCitiesExtracted[$key],
                'ad_link' => $adLinksExtracted[$key],
                'image_link' => $imageLinksExtracted[$key],
                'ad_source' => $source,
                'advertiser' => $advertisersExtracted[$key],
                'ad_date' => $adDatesExtracted[$key],
            ];
            $ads[] = $ad;
        }

        return $ads;
    }
}
