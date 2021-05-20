<?php


namespace App\Crawlers\DataExtractors;

use App\Crawlers\DataExtractors\Helpers\OglasiRoomSurfaceFloor;
use App\Exceptions\DataExtractionException;

class OglasiDataExtractor
{
    /**
	 * Stores the raw result of the crawling process. This will be used as a source to actually get our ad data.
	 *
     * @var \DOMXPath $xpath
     *
     */
    private $xpath;

    public function __construct(\DOMXPath $xpath)
    {
        $this->xpath = $xpath;
    }

	/**
	 *
	 * @return array
	 */
    public function getPrices(): array
	{
        $prices = $this->xpath->query("//div[@class='pull-right visible-md']/div[@class='text-right']/span[@class='text-price']");

        if ($prices) {
            foreach ($prices as $price) {
                $priceWithoutEur = str_replace('EUR', '', $price->textContent);
                $pricesWithoutDots = str_replace('.', '', $priceWithoutEur);
                $pricesWithoutZeros = str_replace(',00', '', $pricesWithoutDots);
                $prices = (float) $pricesWithoutZeros;
                $pricesExtracted[] = $prices;
            }

            return $pricesExtracted;
        }
    }

	/**
	 *
	 * @return array
	 * @throws DataExtractionException
	 */
    public function getAdLinks(): array
	{
        $links = $this->xpath->query("//a[@class='fpogl-list-image']");
        $advertisementLinks = [];

        if($links) {
            foreach ($links as $link) {
                $advertisementLinks[] = 'https://www.oglasi.rs' . $link->getAttribute("href");
            }

            return $advertisementLinks;
        }

		throw new DataExtractionException('links');
	}

	/**
	 *
	 * @return array
	 * @throws DataExtractionException
	 */
	public function getImageLinks(): array
	{
        $links = $this->xpath->query("//div/div/figure/a[@class='fpogl-list-image']/img");
        $imageLinks = [];

        if($links) {
            $myCounter = 0;
            foreach ($links as $link) {
                if (strlen($link->getAttribute("src")) < 2) {
                    $imageLinks[] = 'There is no image link available.';
                }
                $imageLinks[] = $link->getAttribute("src");
            }

            return $imageLinks;
        }

		throw new DataExtractionException('Image links');
	}

	/**
	 *
	 * @return array
	 * @throws DataExtractionException
	 */
    public function getAdDate(): array
	{
        $publishDates = $this->xpath->query("//time");//WORKS!
        $publishDatesExtracted = [];

        if($publishDates) {
            foreach ($publishDates as $date) {
                $publishDatesExtracted[] = $date->getAttribute("datetime");
            }

            return $publishDatesExtracted;
        }

		throw new DataExtractionException('date');
	}

	/**
	 *
	 * @return array
	 * @throws DataExtractionException
	 */
    public function getAdvertiser(): array
	{
        //ADVERTISERS
        $advertisers = $this->xpath->query("//div[@class='visible-sm']/small");
        $advertisersExtracted = [];

        if($advertisers) {
            foreach ($advertisers as $advertiser) {
                $advertisersExtracted[] = $advertiser->textContent;
            }

            return $advertisersExtracted;
        }

		throw new DataExtractionException('advertisers');
	}

	/**
	 * getSurfaceFloorRoomsAge has to deal with the next problem: in one long string we have surface,
	 * room, and floor data. Example:
	 * Sobnost: Jednoiposoban Kvadratura: 31m2 Nivo u zgradi: Prizemlje
	 * Sobnost: Jednoiposoban Kvadratura: 36m2 Stanje objekta: Novogradnja Nivo u zgradi: 3. sprat
	 *
	 * @return array[]
	 * @throws DataExtractionException
	 */
    public function getSurfaceFloorRoomsAge(): array
	{
        //SURFACE + FLOOR + NUMBER OF ROOMS
        $DOMNodeListObject = $this->xpath->query("//div[@style='margin-bottom:16px']/div[@class='row']");
        $surfaceFloorRoomExtracted = [];
        $dataExtractor = new OglasiRoomSurfaceFloor();
        $roomsExtracted = [];
        $surfacesExtracted = [];
        $floorsExtracted = [];
        if($DOMNodeListObject) {
            foreach ($DOMNodeListObject as $DOMElement) {
                $surfaceFloorRoomExtracted[] = $dataExtractor->extractDescription($DOMElement->nodeValue);
            }
            foreach ($surfaceFloorRoomExtracted as $subArray) {
                foreach ($subArray as $key => $value) {
                    if ($key === 'room') {
                        $roomsExtracted[] = $value;
                    }
                    if ($key === 'surface') {
                        $surfacesExtracted[] = $value;
                    }
                    if ($key === 'floor') {
                        $floorsExtracted[] = $value;
                    }
                }
            }

            return [$roomsExtracted, $surfacesExtracted, $floorsExtracted];
        }

		throw new DataExtractionException('links');
	}

	/**
	 * getLocation has to solve the next specific problem: beside the city, there are 2-4 location
	 * data for every ad. Plan to solve this: create a city string variable where the city goes, and
	 * create a location variable, where we will put every existing location data.
	 * Also, sometimes there is only city, and there is no location in the city. So we have to make 2 cases here.
	 *
	 * @return array[]
	 * @throws DataExtractionException
	 */
    public function getLocation(): array
	{
        $cityExtracted = [];
        $locationInCityExtracted = [];
        $allMixedData = $this->extractMixedDataFromCrawler();
        $cityAndLocation = $this->removeDumpFromData($allMixedData);
        //Sometimes there is only city, and there is no location in the city. So we have to make 2 cases here.
        foreach ($cityAndLocation as $subarray) {
            if (count($subarray) === 1 ) {
                $cityExtracted[] = $subarray[0];
                $locationInCityExtracted[] = 'No location data for this ad.';
            } elseif (count($subarray) === 2 ) {
                $cityExtracted[] = $subarray[0];
                $locationInCityExtracted[] = $subarray[1];
            } else {
				throw new DataExtractionException('locations');
            }
        }

        return[$cityExtracted, $locationInCityExtracted];
    }

	/**
	 * - removes some total random empty arrays
	 * - removes "Nekretnine, Prodaja nekretnine, Prodaja stanova"
	 *
	 * @param array $allMixedData
	 *
	 * @return array
	 */
    public function removeDumpFromData(array $allMixedData): array
	{
        $cityAndLocation = [];

        foreach ($allMixedData as $key => $value){

            if (empty($value)) {
                unset($allMixedData[$key]);
            }
        }

        $allMixedDataReindexed = array_values($allMixedData);

        foreach ($allMixedDataReindexed as $subarray) {
            unset($subarray[0]);
            unset($subarray[1]);
            unset($subarray[2]);

            $resetedSubaray = array_values($subarray);
            $cityAndLocation [] = $resetedSubaray;
        }

        return $cityAndLocation;
    }

	/**
	 *
	 * @return array
	 */
    public function extractMixedDataFromCrawler(): array
	{
        $allMixedData = [];
        $DOMNodeList1 = $this->xpath->query("//article/div/div[@class='row']/div[@class='col-sm-8 col-md-9 col-lg-7']/div[@style='margin-bottom:16px']");
        foreach ($DOMNodeList1 as $div) {
            $DOMNodeList2 = $div->getElementsByTagName('a');
            $oneAdArray = [];
            foreach ($DOMNodeList2 as $aTag) {
                if (strlen($aTag->textContent) < 50) {
                    $oneAdArray[] = $aTag->textContent;
                }
            }
            $allMixedData[] = $oneAdArray;
        }

        return $allMixedData;
    }
}
