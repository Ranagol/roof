<?php

namespace App\Http\Controllers\AdControllers;

use App\Models\Ad;
use App\Models\Filter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

abstract class AbstractAdController extends Controller
{
	/**
	 * Contains either 'non-processed', 'starred' or 'dismissed' value.
	 * This will be needed for dynamic url creating in the response.
	 * This is needed for the AdListAdTypeFilter
	 *
	 * @var string $adTypeForUrl
	 */
	protected $adTypeForUrl;

	/**
	 * example: in the NPAdController, the relationship will be = 'nonProcessedAds'.
	 * This property will make this abstract controller able to work with starred, dismissed, etc. ads in its child classes.
	 *
	 * @var string $relationshipForAds
	 */
	protected $relationshipForAds;

	/**
	 * Returns filtered, sorted, paginated ads.
	 * It does in several steps. First it gets all search params from the request, creates a filter from it
	 * and immediately removes the unneeded name, created_at, updated_at data. Then (if the user is logged in)
	 * it gets all starred/dismissed/np ads for him. If the user is not logged in, he gets back all ads. If there is
	 * already a $savedFilterId in the request, then the previously created filter will be overwritten with the one
	 * save filter (which will be retrieved from the db).
	 * This method sends response for two cases:
	 * 1 - search request for Vue components
	 * 2 - standard request for blades, like loading the page
	 *
	 * @param Request $request Laravel HTTP request.
	 * @param null $savedFilterId ID of the filter created and saved by the User.
	 *
	 * @return View|JsonResponse
	 */
	public function index(Request $request, $savedFilterId = null)
	{
		$ads = new Ad();
		$relationship = $this->relationshipForAds;

		//FILTERING THE ADS
		$filter = new Filter($request->all());
		unset($filter['name'], $filter['created_at'], $filter['updated_at']);

		if (Auth::user()) {
			$ads = $ads->$relationship(Auth::user());

			if ($savedFilterId !== null) {
				$filter = Auth::user()
				->filters()
					->where('id', '=', $savedFilterId)
					->first();
			}
		}

		$sortByThis = $request->sortByThis;

		//0-GETTING THE ADS FOR THE RESPONSE
		$ads = $ads->filter($filter)
		->orderAds($sortByThis)
		->paginate(10);

		//1-json response for Vue components
		if ($request->expectsJson() === true) {
			return response()->json($ads);
		}

		//2-standard response for blades,
		$adTypeForUrl = $this->adTypeForUrl;
		$filter = $filter->toArray();

		return view('ads.adList.adList',
			compact(
				'ads',
				'savedFilterId',
				'adTypeForUrl',
				'filter',
			)
		);

	}
}
