<?php

namespace App\Http\Controllers\AdControllers;

use App\Models\Filter;
use Illuminate\Http\Request;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;

class StarredAdController extends AbstractAdController
{
    public $adTypeForUrl = 'starred';
    protected $relationshipForAds = 'starredAds';
}
