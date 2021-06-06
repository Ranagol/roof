<?php

namespace App\Http\Controllers\AdControllers;

use App\Models\Ad;
use App\Models\User;
use App\Repository\Crawler\HaloOglasiCrawlerRepository;
use App\Repository\Crawler\OglasiCrawlerRepository;
use App\Repository\Interfaces\MyTestRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdController extends AbstractAdController
{
	/**
	 * This repository is used for the crawling function.
	 *
	 * @var MyTestRepositoryInterface $repository
	 */
	private $repository;

	public function __construct(MyTestRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * welcomePage loads the welcome/page, with the logged in user's ad lists and with the
	 * counted non processed ads for every saved filter.
	 *
	 * @return Application|Factory|View
	 */
	public function welcomePage()
	{
		if (Auth::user()) {
			$filters = Auth::user()->getSavedFilters();
			$ad = new Ad();
			$filterCount = [];
			foreach ($filters as $filter) {
				$filterCount[$filter->id] = $ad
					->notProcessedAds(Auth::user())
					->getCountForFilter($filter);
			}
		} else {
			$filters     = NULL;
			$filterCount = NULL;
		}

		return view(
			'welcome',
			compact(
				'filters',
				'filterCount'
			)
		);
	}

	/**
	 * showAd is the main controller for the adDetails page. It returns the selected ad, and the selected ad's possible
	 * duplicates.
	 *
	 * @param Request $request
	 * @param  $id
	 *
	 * @return Application|Factory|View
	 */
	public function showAd(Request $request, $id)
	{
		$ad = Ad::findOrFail($id);
		$user = Auth::user();
		$duplicates = $ad
			->findDuplicates($user)
			->removeDismissedAds($user)
			->removeConfirmedDuplicates($user)
			->get();

		return view(
			'ads.adDetails.adDetails',
			compact(
				'ad',
				'duplicates',
			)
		);
	}

	/**
	 * getHalooglasiAds will activate the crawling process from halooglasi.rs
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function getHalooglasiAds(): void
	{
		$repository = new HaloOglasiCrawlerRepository();
		$ads = $repository->getAds();
		$repository->saveAds($ads);
		echo 'Ads were successfully retrieved' . PHP_EOL;
	}

	/**
	 * getOglasiAds will activate the crawling process from oglasi.rs
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function getOglasiAds(): void
	{
		$repository = new OglasiCrawlerRepository();
		$ads = $repository->getAds();
		$repository->saveAds($ads);
		echo 'Ads were successfully retrieved' . PHP_EOL;
	}

	/**
	 * setDismissedInPivotTable function sets dismissed to true, if a user dismisses an ad,
	 * and sets dismissed to false, if the user stars an ad.
	 *
	 * @param Request $request
	 *
	 * @return void
	 */
	public function setDismissedInPivotTable(Request $request): void
	{
		$adId = $request->adId;
		$dismissed = $request->dismissed;//this is 0 or 1, aka true or false

		/* @var User $user */
		$user = Auth::user();

		//syncWithoutDetaching is a way how to update the the dismissed column in the ad_user pivot table
		$user->allStarredAndDismissedAds()->syncWithoutDetaching([$adId => ['dismissed' => $dismissed]]);
	}

	/**
	 * NotDuplicate will mark an ad as a confirmed not duplicate, for the given user, for the given target ad.
	 * Note: ad_id_1 is the base ad, the current ad that is watched in the adDetails page.
	 * ad_id_2 is the possible duplicate ad, that will be either confirmed as a duplicate, or confirmed as a not duplicate.
	 *
	 * @param Request $request
	 *
	 * @return void
	 */
	public function notDuplicate(Request $request): void
	{
		$ad_id_1 = $request->ad_id_1;
		$ad_id_2 = $request->ad_id_2;

		/* @var User $user */
		$user = Auth::user();

		//Mark the ad as NOT A duplicate
		DB::table('duplicates')->insert(
			[
				'user_id' => $user->id,
				'ad_id_1' => $ad_id_1,
				'ad_id_2' => $ad_id_2,
			]
		);
	}

	/**
	 *	Testing purpose.
	 *
	 * @return void
	 */
	public function myTest()
	{
		$this->repository->echoSomethingFromInterface();
	}

	// public function getPossibleDuplicates(Request $request){
	// 	// print_r('getPossibleDuplicates was activated'); die;
	// 	$user = Auth::user();
	// 	$adId = $request->adId;
	// 	// print_r($adId); die;
	// 	$ad = Ad::findOrFail($adId);
	// 	// print_r($ad); die;
	// 	$possibleDuplicateAds = $ad->findDuplicates($user)
	// 		->removeDismissedAds($user)
	// 		->removeConfirmedDuplicates($user)
	// 		->get();
	// 	print_r($possibleDuplicateAds); die;

	// 	return response()->json($possibleDuplicateAds);
	// }
}
