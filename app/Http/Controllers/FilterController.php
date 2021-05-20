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
        $filter = new Filter($allSearchTerms);
        $filter->save();
    }


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $savedFilterId
	 */
    public function destroy($savedFilterId): void
	{
        var_dump($savedFilterId);
        $filter = Filter::findOrFail($savedFilterId);
        $filter->delete();
    }
}
