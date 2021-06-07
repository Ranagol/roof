<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Support\Facades\Auth;
use App\Repository\Interfaces\UserRepositoryInterface;


class UserController extends Controller
{
    /**
     * Will store the UserRepository object, in accordance with the repository pattern.
     *
     * @var UserRepository $repository
     */
    private $repository;

    public function __construct(UserRepositoryInterface $repository) {
        $this->repository = $repository;
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (Auth::user()) {
            $user = Auth::user();
			$filters = $user->getSavedFilters();
			$ad = new Ad();
			$filterCount = [];
			foreach ($filters as $filter) {
				$filterCount[$filter->id] = $ad
					->notProcessedAds($user)
					->getCountForFilter($filter);
			}
            //THE OLD FUNCTION, REPLACED BY REPOSITORY PATTERN
            // $dismissedAds = $user->dismissedAds->count();
            // $starredAds = $user->starredAds->count();
            //NEW REPOSITORY PATTERN FUNCTIONS
            $dismissedAds = $this->repository->dismissedAds($user)->count();
            $starredAds = $this->repository->starredAds($user)->count();

            $userData = [
                'name' => $user->name,
                'email' => $user->email,
            ];
		} else {
			$filters = null;
			$filterCount = null;
            $dismissedAds = null;
            $starredAds = null;
            $userData = null;
		}

		return view(
			'ads.yourStuff.yourStuff',
			compact(
				'filters',
				'filterCount',
                'dismissedAds',
                'starredAds',
                'userData',
			)
		);
    }
}
