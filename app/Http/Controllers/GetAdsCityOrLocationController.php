<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAdsCityOrLocationController extends Controller
{
	/**
	 * __invoke is used for the Autocomplete function, for getting suggestion data from the db. 
	 * For every typed characted
	 * in the input field this function will activate again and again.
	 * Example for searchString: n no nov novi...
	 *
	 * @param Request $request Laravel request
	 * @param string  $type What are we searching for. City  or location. Example: type = city.
	 *
	 * @return JsonResponse
	 */
	public function __invoke(Request $request, string $type): JsonResponse
	{
		$suggestions = $this->getSuggestionsFromDb($request, $type);
		$capitalizedSuggestions = $this->capitalizeSuggestions($suggestions);
		
		return response()->json($capitalizedSuggestions);
    }

	/**
	 * Gets the suggestions from the db.
	 * Example: if the user types 'b' in the city search field, this function will return all 
	 * cities from the city column, which name is starting with 'b'.
	 *
	 * @param Request $request
	 * @param string $type
	 * @return Object
	 */
	private function getSuggestionsFromDb(Request $request, string $type):Object
	{
		$searchString = mb_strtolower($request->searchString);
		$results = Ad::where(
				$type,
				'like',
				$searchString . '%'
			)
			->distinct($type)
			->get($type);

		return $results;
	}

	/**
	 * Since all the cities and locations in the db are stored in lower case, we need
	 * to capitalize them before returning them to the frontend.
	 * Example: instead of beograd we want to return Beograd.
	 *
	 * @param Object $results
	 * @return Array
	 */
	private function capitalizeSuggestions(Object $results):Array
	{
		$resultsArray = $results->toArray();
		$newResponse = [];
		foreach ($resultsArray as $array1) {
			foreach ($array1 as $keyCityOrLocation => $valueCityOrLocation) {
				$newResponse[][$keyCityOrLocation] = ucwords($valueCityOrLocation);
			}
		}

		return $newResponse;
	}
}
