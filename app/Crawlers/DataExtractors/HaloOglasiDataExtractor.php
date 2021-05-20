<?php


namespace App\Crawlers\DataExtractors;


use App\Crawlers\DataExtractors\Helpers\HaloOglasiSurfaceFloorRooms;
use App\Exceptions\DataExtractionException;
use http\Exception\RuntimeException;

class HaloOglasiDataExtractor
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
	 * Extracts prices from the raw crawling data.
	 *
	 * @return array
	 */
    public function getPrices(): array
	{
        $prices = $this->xpath->query("//div[@class='central-feature']");
        $pricesExtracted = [];
        foreach ($prices as $price) {
            $priceWithoutDot = str_replace('.', '', $price->textContent);
            $priceWithoutEurSign = str_replace('€', '', $priceWithoutDot);
            $price = (float) $priceWithoutEurSign;
            $pricesExtracted[] = $price;
        }

        return $pricesExtracted;
    }

	/**
	 * Extracts prices by surface from the raw crawling data.
	 *
	 * @return array
	 */
    public function getPricesBySurface(): array
	{
        $pricesBySurface = $this->xpath->query("//div[@class='price-by-surface']");
        $pricesBySurfaceExtracted = [];
        foreach ($pricesBySurface as $unitPrice) {
            $priceM2 = str_replace(' €/m2', '', $unitPrice->textContent);
            $priceM2 = str_replace('.', '', $priceM2);
            $priceM2 = (float) $priceM2;
            $pricesBySurfaceExtracted[] = $priceM2;
        }

        return $pricesBySurfaceExtracted;
    }

	/**
	 * Extracts dates from the raw crawling data.
	 *
	 * @return array
	 */
    public function getAdDates(): array
	{
        $adDates = $this->xpath->query("//span[@class='publish-date']");
        $adDatesExtracted = [];
        foreach ($adDates as $span) {
            $adDatesExtracted[] = date("Y-m-d", strtotime($span->nodeValue));
        }

        return $adDatesExtracted;
    }

	/**
	 * Extracts advertiser from the raw crawling data.
	 *
	 * @return array
	 */
    public function getAdvertisers(): array
	{
        $advertisers = $this->xpath->query("//span[@class='basic-info']");
        $advertisersExtracted = [];
        foreach ($advertisers as $span) {
            $advertisersExtracted[] = $span->textContent;
        }

        return $advertisersExtracted;
    }

    /**
     * getAdAndImageLink is extracting ad and image link at once, because these two are in the same
     * source.
     *
     * @return array[]
     * @throws DataExtractionException Error extracting links.
     */
    public function getAdAndImageLink(): array
	{
        $links = $this->xpath->query("//a[@class='a-images']");
        $imageLinksExtracted = [];
        $advertisementLinksExtracted = [];

        if($links) {
            foreach ($links as $link) {
                $advertisementLinksExtracted[] = 'https://www.halooglasi.com' . $link->getAttribute("href");
                foreach ($link->childNodes as $image) {
                    $imageLinksExtracted[] = $image->getAttribute("src");
                }
            }

            return [ $imageLinksExtracted, $advertisementLinksExtracted];
        }

		throw new DataExtractionException('links');
	}

	/**
	 * getSurfacesRoomsFloors
	 *
	 *  There are three product features: kvadratura, broj soba i spratnost.
	 * Examples '86,55 m2Kvadratura'
	 * '4.0 Broj soba'
	 * 'IV/7 Spratnost'
	 * --- this means the 4th floor of 7 floorsthis data will have to be cleaned, before writing into db
	 *
	 * @return array[]
	 * @throws DataExtractionException
	 */
    public function getSurfacesRoomsFloors(): array
	{
        $productFeatures = $this->xpath->query("//ul[@class='product-features ']");

		if(!$productFeatures) {
			throw new DataExtractionException('Testin testing');
		}

        $allMixedData = [];
        $surfacesExtracted = [];
        $roomsExtracted = [];
        $floorsExtracted = [];

        $extractFromString = new HaloOglasiSurfaceFloorRooms();

		foreach ($productFeatures as $ul) {
			$allMixedData[] = $extractFromString->extractDescription($ul->textContent);
		}
		foreach ($allMixedData as $subArray) {
			foreach ($subArray as $key => $value) {
				if ($key === 'surface') {
					$surfacesExtracted[] = $value;
				}
				if ($key === 'room') {
					$roomsExtracted[] = $value;
				}
				if ($key === 'floor') {
					$floorsExtracted[] = $value;
				}
			}
		}

		return [$surfacesExtracted, $roomsExtracted, $floorsExtracted];
    }

	/**
	 * getLocations has to solve the next specific problem: beside the city, there are 2-4 location
	 * data for every ad. Plan to solve this: create a city string variable where the city goes, and
	 * create a location variable, where we will put every existing location data.
	 *
	 * @return array[]
	 * @throws DataExtractionException
	 */
    public function getLocations(): array
	{
        $locations = $this->xpath->query("//ul[@class='subtitle-places']");
        $cityExtracted = [];
        $locationInCityExtracted = [];

        if($locations) {
            foreach ($locations as $location) {
                $cityExtracted[] = $this->removeSpaces($location->childNodes[0]->textContent);
                $locationInCityExtracted[] = $location->childNodes[1]->textContent . ' / ' . $location->childNodes[2]->textContent;
            }

            return [$cityExtracted, $locationInCityExtracted];
        }

		throw new DataExtractionException('locations');
	}

	/**
	 * RemoveSpaces removes two invisible space-like characters from the right side of the string.
	 * Problem description: Beograd**
	 * After using removeSpaces: Beograd
	 *
	 * @param String $string
	 *
	 * @return mixed
	 */
    private function removeSpaces(String $string)
	{
		$arrayOfLetters = str_split($string);
		unset($arrayOfLetters[count($arrayOfLetters)-1], $arrayOfLetters[count($arrayOfLetters)-1]);

		return implode($arrayOfLetters);
	}

	/**
	 *
	 * @return string
	 */
    public function getAdSource(): string
	{
        return 'Halo Oglasi';
    }
}
