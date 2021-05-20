<?php

namespace App\Http\Controllers\AdControllers;

use App\Models\Ad;
use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DismissedAdController extends AbstractAdController
{
	/**
	 * @var string $adTypeForUrl
	 */
    public $adTypeForUrl = 'dismissed';

	/**
	 * @var string $relationshipForAds
	 */
    protected $relationshipForAds = 'dismissedAds';
}
