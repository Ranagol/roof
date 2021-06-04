<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
		//use this for de-normalization: https://lodash.com/docs#startCase
		$searchString = mb_strtolower($request->searchString);
		$results = Ad::where(
				$type,
				'like',
				$searchString . '%'
			)
			->distinct($type)
			->get($type);
		
		return response()->json($results);
    }
}
