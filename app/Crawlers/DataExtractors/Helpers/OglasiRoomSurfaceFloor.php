<?php


namespace App\Crawlers\DataExtractors\Helpers;

use App\Exceptions\DataExtractionException;
use function PHPUnit\Framework\throwException;

/**
 * Class OglasiRoomSurfaceFloor
 * The basic problem that we want to solve with this class is that in one string sentence we have
 * 3 vital information: surface, floor and rooms. We need to extract these info from this sentence.
 *
 * @package App\Crawlers\DataExtractors\Helpers
 */
class OglasiRoomSurfaceFloor
{
	/**
	 * @var string $difference
	 */
    public $difference = 'Stanje objekta';

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
     *
     * @param string $stringSentence
     *
     * @return false|string[]
     */
    private function makeArray(string $stringSentence)
    {
        $stringSentence = trim(preg_replace('!\s+!', ' ', $stringSentence));
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
        $keyBeforeRoom = array_search('Sobnost: ', $arrayOfWords);
        $numberOfRooms = $arrayOfWords[$keyBeforeRoom + 1];//Dvoiposoban or Četvorosoban
        $numberOfRooms = $this->convertStringToFloat($numberOfRooms);

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
        $keyBeforeSurface = array_search('Kvadratura:', $arrayOfWords);
        $surface = $arrayOfWords[$keyBeforeSurface + 1];//95m2
        $surface = $this->removeAllWhiteSpaces($surface);
        $surface = str_replace('m2', '', $surface);

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
        $keyBeforeFloor = array_search('zgradi:', $arrayOfWords);
        $floor= $arrayOfWords[$keyBeforeFloor + 1];
        $floor = $this->removeAllWhiteSpaces($floor);
        $floor = str_replace('.', '', $floor);

		return (float) $floor;
    }

	/**
	 * Removes whitespaces.
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
	 * convertStringToFloat has to convert strings like 'Jednosoban' or 'Jednoiposoban" to floats like
	 * 1 or 1.5
	 *
	 * @param string $string
	 *
	 * @return float|int|string
	 * @throws DataExtractionException
	 */
    private function convertStringToFloat(string $string)
    {
        $returnThis = 'someTestValue';

        switch ($string) {
            case 'Garsonjera':
                $returnThis = 1;
                break;
            case 'Jednosoban':
                $returnThis = 1;
                break;
            case 'Jednoiposoban':
                $returnThis =  1.5;
                break;
            case 'Dvosoban':
                $returnThis = 2;
                break;
            case 'Dvoiposoban':
                $returnThis = 2.5;
                break;
            case 'Trosoban':
                $returnThis = 3;
                break;
            case 'Troiposoban':
                $returnThis = 3.5;
                break;
            case 'Četvorosoban':
                $returnThis = 4;
                break;
            case 'Petosoban':
                $returnThis = 5;
                break;
            default:
				throw new DataExtractionException('Halo-oglasi room converting');

        }

        return $returnThis;
    }
}
