<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FilterController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 *
	 * @throws ValidationException
	 */
    public function store(Request $request): void
	{
        $this->validate($request, [
            'name' => 'required|min:3',
            'city' => 'string|nullable',
            'location_in_city' => 'string|nullable',
            'min_rooms' => 'numeric|gt:0|nullable',
            'max_rooms' => 'numeric|gt:0|nullable',
            'min_surface' => 'numeric|gt:0|nullable',
            'max_surface' => 'numeric|gt:0|nullable',
            'min_floor' => 'numeric|gt:0|nullable',
            'max_floor' => 'numeric|gt:0|nullable',
            'min_price' => 'numeric|gt:0|nullable',
            'max_price' => 'numeric|gt:0|nullable',
            'min_price_by_surface' => 'numeric|gt:0|nullable',
            'max_price_by_surface' => 'numeric|gt:0|nullable',
        ]);

        $allSearchTerms = $request->all();
        $userId = Auth::user()->id;
        $allSearchTerms['user_id'] = $userId;
        $allSearchTerms2 = $this->deCapitalizeCityAndLocation($request, $allSearchTerms);
        $filter = new Filter($allSearchTerms2);
        $filter->save();
    }

    /**
     * City and location are in lowercase in the db. Example: Novi Sad.
	 * City and location are uppercase in the request.
	 * So, if we want to perform a filtering or search in the db, with the search terms
	 * city and location, then we must transform them to be lowercase.
     *
     * @param Request $request
     * @param Array $allSearchTerms
     * @return array
     */
	private function deCapitalizeCityAndLocation(Request $request, Array $allSearchTerms): array
	{
		if(isset($allSearchTerms['city'])) {
			$allSearchTerms['city'] =  mb_strtolower($request->city);
		}
		if(isset($allSearchTerms['location_in_city'])) {
			$allSearchTerms['location_in_city'] =  mb_strtolower($request->location_in_city);
		}

		return $allSearchTerms;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $savedFilterId
	 */
    public function destroy($savedFilterId): void
	{
        $filter = Filter::findOrFail($savedFilterId);
        $filter->delete();
    }
}
