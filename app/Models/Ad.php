<?php

namespace App\Models;

use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ad extends Model
{
	use HasFactory;

	/**
	 * @var string[] $guarded
	 */
	protected $guarded = ['id'];


	/**
	 * Appends 'has_duplicates' to the ad object, like as if it has a has_
	 * duplicates column in the db table. The value of the has_duplicate 
	 * will be determined by the getHasDuplicatesAttribute() accessor below.
	 *
	 * @var array
	 */
	protected $appends = ['has_duplicates'];


	/**
	 * For every ad, we need a boolean, if this ad has possible duplicates.
	 * Example: every ad will have something like this: has_duplicates = true;
	 * Just like as if in the ads table we had a has_duplicates column (that we don't have actually.)
	 * This is determined by $this->findDuplicates();
	 *
	 * @return boolean
	 */
	public function getHasDuplicatesAttribute(): bool
	{
		$user = Auth::user();

		return $this
			->findDuplicates($user)
			->removeDismissedAds($user)
			->removeConfirmedDuplicates($user)
			->count() > 0;
	}


	/**
	 * users is just a simple relationship.
	 *
	 * @return BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany(User::class)->withPivot('dismissed');
	}

	/**
	 * @return BelongsToMany
	 */
	public function usersOfDuplicateAds()
	{
		return $this->belongsToMany(User::class, 'duplicates');
	}

	/**
	 * notProcessedAds are ads that are not dismissed or starred yet. So, this function
	 * will return all the ads, that are not dismissed or starred by th user.
	 *
	 * @param User $user User we are checking the processed Ads for.
	 *
	 * @return Builder
	 */
	public function notProcessedAds(User $user): Builder
	{
		$allStarredAndDismissedAdIds = $user->allStarredAndDismissedAds()->get(['id'])->pluck('id');

		return $this->whereNotIn('id', $allStarredAndDismissedAdIds);
	}

	/**
	 * starredAds will return all ads that are starred by the user. In db, if an ad is starred,
	 * then its value in the pivot dismissed column is false.
	 *
	 * @param User $user User we are checking the processed Ads for.
	 *
	 * @return Builder
	 */
	public function starredAds(User $user): Builder
	{
		$allStarredAdIds = $user->allStarredAndDismissedAds()
			->wherePivot('dismissed', '=', FALSE)
			->get(['id'])
			->pluck('id');

		return $this->whereIn('id', $allStarredAdIds);
	}

	/**
	 * dismissedAds will return all ads that are dismissed by the user. In db, if an ad is dismissed,
	 * then its value in the pivot dismissed column is true.
	 *
	 * @param User $user User we are checking the processed Ads for.
	 *
	 * @return Builder
	 */
	public function dismissedAds(User $user): Builder
	{
		$allDismissedAdIds = $user->allStarredAndDismissedAds()
			->wherePivot('dismissed', '=', TRUE)
			->get(['id'])
			->pluck('id');

		return $this->whereIn('id', $allDismissedAdIds);
	}

	/**
	 * ads will return all ads that belong to the given user, together with the dismissed column,
	 * that is actually not in the ads table, but in the ad_user pivot table.
	 *
	 * @return BelongsToMany
	 */
	public function allStarredAndDismissedAds(): BelongsToMany
	{
		return $this->belongsToMany(User::class)->withPivot('dismissed');
	}

	/**
	 * scopeFilter filters ads by search params like city, location, room, price.
	 *
	 * @param Builder $query
	 * @param Filter  $filter
	 *
	 * @return BuildsQueries|Builder|mixed
	 */
	public function scopeFilter(Builder $query, Filter $filter)
	{
		return $query->when(
			$filter->city, function ($query, $city) {
			return $query->where('city', 'LIKE', '%' . $city . '%');
		})
			->when(
				$filter->location_in_city, function ($query, $location) {
				return $query->where('location_in_city', 'LIKE', '%' . $location . '%');
			})
			->when(
				$filter->min_rooms, function ($query, $minRooms) {
				return $query->where('number_of_rooms', '>=', $minRooms);
			})
			->when(
				$filter->max_rooms, function ($query, $maxRooms) {
				return $query->where('number_of_rooms', '<=', $maxRooms);
			})
			->when(
				$filter->min_surface, function ($query, $minSurface) {
				return $query->where('surface', '>=', $minSurface);
			})
			->when(
				$filter->max_surface, function ($query, $maxSurface) {
				return $query->where('surface', '<=', $maxSurface);
			})
			->when(
				$filter->min_floor, function ($query, $minFloor) {
				return $query->where('floor', '>=', $minFloor);
			})
			->when(
				$filter->max_floor, function ($query, $maxFloor) {
				return $query->where('floor', '<=', $maxFloor);
			})
			->when(
				$filter->min_price, function ($query, $minPrice) {
				return $query->where('price', '>=', $minPrice);
			})
			->when(
				$filter->max_price, function ($query, $maxPrice) {
				return $query->where('price', '<=', $maxPrice);
			})
			->when(
				$filter->min_price_by_surface, function ($query, $minPriceBySurface) {
				return $query->where('price_by_surface', '>=', $minPriceBySurface);
			})
			->when(
				$filter->max_price_by_surface, function ($query, $maxPriceBySurface) {
				return $query->where('price_by_surface', '<=', $maxPriceBySurface);
			});
	}


	public function scopeFilter2(Builder $query, Filter $filter){
		
		foreach ($filter as $filterKey => $filterValue) {
			query->where('$filterKey', 'LIKE', '%' . $filterValue . '%');
		}

		return $query;
		
		
	}

	



	/**
	 * ScopeFilterByFilterId will find the users saved ad list with the help of the filter / ad list id number.
	 *
	 * @param Builder $query
	 * @param         $id
	 *
	 * @return BuildsQueries|Builder|mixed|null
	 */
	public function scopeFilterByFilterId(Builder $query, $id)
	{
		$filter = Auth::user()
			->filters()
			->where('id', '=', $id)
			->first();

		if ($filter === NULL) {
			return NULL;
		}

		return $this->scopeFilter($query, $filter);
	}

	/**
	 * ScopeGetCountForFilter receives all the non processed ads for a given user, and a saved filter. This filter
	 * will be used to find the matching ads from the non processed ads. By counting these ads, we will have a number of
	 * non processed ads for the given user, and this number will be displayed on the home page, for every saved ad list.
	 *
	 * @param Builder $query
	 * @param Filter  $filter
	 *
	 * @return mixed
	 */
	public function scopeGetCountForFilter(Builder $query, Filter $filter)
	{
		return $query->filter($filter)
			->select('id')
			->count();
	}

	/**
	 * scopeOrderAds is used to sort the ads, based on the parameters that the user chose. Example:
	 * sort the ads by their price, descending.
	 * From Vue, we are sending
	 * an info how should the ads be sorted. Example we send this request:
	 * http://127.0.0.1:8000/ad-list/non-processed/5?sortByThis=price.asc
	 * note the price.asc. That means that we want the ads sorted by the price column, ascending.
	 * we extract this info to the $sortByThis, and this variable will be used, to define the column name
	 * (in this case: price) and the sort direction (in this case: asc).
	 *
	 * @param Builder     $query
	 * @param string|null $sortByThis
	 *
	 * @return Builder
	 */
	public function scopeOrderAds(Builder $query, string $sortByThis = NULL)
	{
		if ($sortByThis !== NULL) {
			[$sortColumn, $sortOrder] = explode('.', $sortByThis);

			return $query->orderBy($sortColumn, $sortOrder);
		}

		return $query;
	}



	/** DUPLICATE ADS RELATED FUNCTIONS */

	

	/**
	 *these will be the criteria to find possible (not yet confirmed) duplicates, based on:
	 * surface, nr of rooms, floor, city.
	 * There is one ad, for which we want to find duplicates. We take this ad, and create a $filter from it.
	 * This $filter will be used to filter all the ads, and to find the ads that have same surface, nr of rooms, floor, city.
	 * The logic here is that for example from the surface value we create below min_surface and
	 * max_surface values. Because we need this for the search.
	 * There is no Ad $ad argument here, since we are looking for the current ad's duplicate like this: $ad->findDuplicates().
	 *
	 * ADDITIONAL RULES FOR DUPLICATES
	 * do not search for duplicates in dismissed ads, only search in NP ads and S ads.
	 * do not display ads that are in the duplicates table. These ads in the duplicates table are actually confirmed
	 * NOT duplicates.
	 *
	 * @return BuildsQueries|Builder|mixed
	 */
	public function findDuplicates(User $user)
	{
		$filter  = new Filter();
		$filter->min_surface = $this->surface;
		$filter->max_surface = $this->surface;
		$filter->min_rooms = $this->number_of_rooms;
		$filter->max_rooms = $this->number_of_rooms;
		$filter->min_floor = $this->floor;
		$filter->max_floor = $this->floor;
		$filter->city = $this->city;
		$query = self::where('id', '!=', $this->id);
		$possibleDuplicateAds = $this->scopeFilter($query, $filter);

		return $possibleDuplicateAds;
	}

	/**
	 * scopeRemoveDismissedAds removes the dismissed ads from the $query that was received as an argument.
	 *
	 * @param Builder $query
	 * @param User    $user
	 *
	 * @return Builder
	 */
	public function scopeRemoveDismissedAds(Builder $query, User $user): Builder
	{
		$allDismissedAdIds = $user->allStarredAndDismissedAds()
			->wherePivot('dismissed', '=', TRUE)
			->get(['id'])
			->pluck('id');

		return $query->whereNotIn('id', $allDismissedAdIds);
	}

	/**
	 * scopeRemoveConfirmedDuplicates removes confirmed duplicate ads from the received $query. All confirmed duplicate
	 * ads are listed in the duplicates
	 *
	 * @param Builder $query
	 * @param User    $user
	 *
	 * @return Builder
	 */
	public function scopeRemoveConfirmedDuplicates(Builder $query, User $user): Builder
	{
		$adIdsInInDuplicatesTable = DB::table('duplicates')
			->where('user_id', $user->id)
			->where('ad_id_1', $this->id)
			->get(['ad_id_2']);
		$arrayOfIds = [];
		foreach ($adIdsInInDuplicatesTable as $ad) {
			$arrayOfIds[] = $ad->ad_id_2;
		}

		return $query->whereNotIn('id', $arrayOfIds);
	}
}
