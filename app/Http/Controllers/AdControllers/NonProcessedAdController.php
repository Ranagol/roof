<?php

namespace App\Http\Controllers\AdControllers;

use App\Models\Ad;
use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NonProcessedAdController extends AbstractAdController
{
    /**
     * @var string $adTypeForUrl
	 *
     * This string will be used to create a url on the blade side.
     * Example for the url: http://127.0.0.1:8000/ad-list/non-processed/5
     */
    public $adTypeForUrl = 'non-processed';

    /**
     * @var string $relationshipForAds
	 *
     * The string 'notProcessedAds' will be used to call Ad::notProcessedAds() function,
     * since this controller is the NonProcessedAdController. The StarredAdController will have a
     * similar property, but the will have 'starredAds' value, since it will call Ad::starredAds().
     */
    protected $relationshipForAds = 'notProcessedAds';

}
