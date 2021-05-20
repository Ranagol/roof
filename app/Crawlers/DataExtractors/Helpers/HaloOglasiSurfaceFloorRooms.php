<?php


namespace App\Crawlers\DataExtractors\Helpers;

/**
 * Class HaloOglasiSurfaceFloorRooms
 * The basic problem that we want to solve with this class is that in one string sentence we have
 * 3 vital informations: surface, floor and rooms. We need to extract these info from this sentence.
 * @package App\Crawlers\DataExtractors\Helpers
 */
class HaloOglasiSurfaceFloorRooms
{

    /**
     * extractDescription is extracting ad description from a string sentence.
     *
     * @param string $stringSentence
     *
     * @return string[]
     */
    public function extractDescription(string $stringSentence): array
	{
        $descriptions = [
            'room' => '',
            'surface' => '',
            'floor' => '',

        ];

        $arrayOfWords = $this->makeArray($stringSentence);
        $descriptions['room'] =$this->extractRoom($arrayOfWords);
        $descriptions['surface'] =$this->extractSurface($arrayOfWords);
        $descriptions['floor'] =$this->extractFloor($arrayOfWords);

        return $descriptions;
    }

    /**
     * makeArray makes array from a string sentence.
     * example of the sentence: 175 m2Kvadratura3.5 Broj sobaV/5 Spratnost. We have to separate these
     * words with space.So we could extract the needed info.
     * @url: https://stackoverflow.com/questions/16619181/php-trying-to-figure-out-how-to-extract-words-from-a-string
	 *
     * @param string $stringSentence
     *
     * @return false|string[]
     */
    private function makeArray(string $stringSentence)
    {
        $stringSentence = str_replace('m2Kvadratura', 'm2Kvadratura ', $stringSentence);
        $stringSentence = str_replace('Broj soba', 'Brojsoba ', $stringSentence);

        return explode(" ", $stringSentence);
    }

    /**
     * extractRoom returns the data about the number of the rooms.
     *
     * @param array $arrayOfWords
     *
     * @return float
     */
    private function extractRoom(array $arrayOfWords): float
	{
        $numberOfRooms = $arrayOfWords[1];
        $numberOfRooms = $this->removeAllWhiteSpaces($numberOfRooms);
        $numberOfRooms = str_replace(' Brojsoba', '', $numberOfRooms);

		return (float) $numberOfRooms;
    }

    /**
     * extractSurface returns the surface of the ads.
     *
     * @param array $arrayOfWords
     *
     * @return float
     */
    private function extractSurface(array $arrayOfWords): float
	{
        $surface = $arrayOfWords[0];
        $surface = $this->removeAllWhiteSpaces($surface);
        $surface = str_replace(' m2Kvadratura', '', $surface);

		return (float) $surface;
    }

    /**
     * extractFloor returns the floor of the ads
     *
     * @param array $arrayOfWords
     *
     * @return float
     */
    private function extractFloor(array $arrayOfWords): float
	{
        $floor = $arrayOfWords[2];
        $floor = $this->removeAllWhiteSpaces($floor);
        $floor = str_replace('Spratnost', '', $floor);
        $floor = $this->removeArabicNumbers($floor);
        $floor = str_replace('/', '', $floor);
        $floor = $this->convertRomanToArabicNumber($floor);

		return (float) $floor;
    }

	/**
	 * RemoveAllWhiteSpaces removes possible multiple whitespaces.
	 *
	 * @param string $string
	 *
	 * @return array|string|string[]|null
	 */
    private function removeAllWhiteSpaces(string $string)
    {
		return preg_replace('/\s+/', '', $string);
    }

	/**
	 * RemoveArabicNumbers removes unnecessary numbers from the floor data.
	 * Example for floor data: XI/14.
	 *
	 * @param string $string
	 *
	 * @return array|string|string[]|null
	 */
    private function removeArabicNumbers(string $string)
    {
        //https://stackoverflow.com/questions/14236148/how-to-remove-all-numbers-from-string
		return preg_replace('/[0-9]+/', '', $string);

    }

	/**
	 * convertRomanToArabicNumber converts the floor data from roman numbers to arabic number.
	 * Example for floor data: XI/14.
	 *
	 * @param string $string
	 *
	 * @return int
	 */
    private function convertRomanToArabicNumber(string $string): int
	{
        //https://stackoverflow.com/questions/6265596/how-to-convert-a-roman-numeral-to-integer-in-php
        $romans = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1,
        ];

        $result = 0;
        foreach ($romans as $key => $value) {
            while (strpos($string, $key) === 0) {
                $result += $value;
                $string = substr($string, strlen($key));
            }
        }

        return $result;
    }
}
