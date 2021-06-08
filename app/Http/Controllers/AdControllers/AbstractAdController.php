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

	protected $savedFilterId;

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
		$this->savedFilterId = $savedFilterId;//ezt ignorald... ez nem lesz
		$filter = $this->createFilter($request);
		$ads = $this->getAds();
		$ads = $this->filterSortPaginateAds($request, $ads, $filter);

		return $this->returnResponse($request, $ads, $filter);
	}

	private function returnResponse(Request $request, $ads, Filter $filter)
	{
		if ($request->expectsJson() === true) {

			return $this->returnResponseForVueComponents($ads);
		}

		return $this->returnResponseForBlade($filter, $ads);
	}

	private function returnResponseForBlade(Filter $filter, $ads)
	{
		//2-standard response for blades,
		$adTypeForUrl = $this->adTypeForUrl;
		$filter = $filter->toArray();
		$savedFilterId = $this->savedFilterId;

		return view('ads.adList.adList',
			compact(
				'ads',
				'savedFilterId',
				'adTypeForUrl',
				'filter',
			)
		);
	}

	private function returnResponseForVueComponents($ads)
	{
		return response()->json($ads);
	}

	private function filterSortPaginateAds(Request $request, $ads, Filter $filter)
	{
		//0-Filter, sort, paginate ads.
		$sortByThis = $request->sortByThis;
		$ads = $ads->filter($filter)
		->orderAds($sortByThis)
		->paginate(10);

		return $ads;
	}

	private function getAds()
	{
		//Get S, NP, D ads for the given user
		$ads = new Ad();
		$relationship = $this->relationshipForAds;
		if (Auth::user()) {
			$ads = $ads->$relationship(Auth::user());
		}

		return $ads;
	}

	/**
	 * City and location are in lowercase in the db. Example: Novi Sad.
	 * City and location are uppercase in the request.
	 * So, if we want to perform a filtering or search in the db, with the search terms
	 * city and location, then we must transform them to be lowercase.
	 *
	 * @param Request $request
	 * @param Filter $filter
	 * @return Filter
	 */
	private function deCapitalizeCityAndLocation(Request $request, Filter $filter):Filter
	{
		if(isset($request->city)) {
			$filter->city =  mb_strtolower($request->city);
		}
		if(isset($request->location_in_city)) {
			$filter->location_in_city =  mb_strtolower($request->location_in_city);
		}

		return $filter;
	}

	private function createFilter(Request $request)
	{
		// print_r($this->savedFilterId); die;
		if ($this->savedFilterId !== null) {
			$filter = Auth::user()
			->filters()
				->where('id', '=', $this->savedFilterId)
				->first();
		} else {
			$filter = new Filter($request->all());
			
		}
		$filter = $this->deCapitalizeCityAndLocation($request, $filter);
		// var_dump($filter->toArray()); die;
		
		unset($filter['created_at'], $filter['updated_at']);

		return $filter;
	}
}
