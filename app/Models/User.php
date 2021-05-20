<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
	 * ads = allStarredAndDismissedAds() -- kinda
	 * allStarredAndDismissedAds() will return all ads that belong to the given user, together with the dismissed column,
     * that is actually not in the ads table, but in the ad_user pivot table.
	 * This function returns starred and dismissed ads only. Why? Because only starred and dismissed ads belongs to a
	 * user. The non-processed ads are not belonging to a user, since the user did not processed them yet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function allStarredAndDismissedAds()
    {
        //returnnig all ads from ads table, together with their value in the ad_user pivot table
        return $this->belongsToMany(Ad::class)->withPivot('dismissed');
    }

    //********************************************************************************************************
    public function duplicateAds(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Ad::class, 'duplicates');
	}



    /**
     * starredAds will return all ads that are starred by the user. In db, if an ad is starred,
     * then its value in the pivot dismissed column is false.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function starredAds()
    {
        //returning all ads from ads table, that have false in the dissmissed column in the ad_user pivot table
        return $this->allStarredAndDismissedAds()->wherePivot('dismissed', '=', false);
    }

    /**
     * dismissedAds will return all ads that are dismissed by the user. In db, if an ad is dismissed,
     * then its value in the pivot dismissed column is true.
     *
     * @return mixed
     */
    public function dismissedAds()
    {
        //returning all ads from ads table, that have true in the dissmissed column in the ad_user pivot table
        return $this->allStarredAndDismissedAds()->wherePivot('dismissed', '=', true);
    }

    /**
     * notProcessedAds will return all the ads, that are not dismissed or starred by th user.
     *
     * @return mixed
     */
    public function notProcessedAds()
    {
        //get all dismissed ad ids
        $allStarredAndDismissedAdIds = $this->allStarredAndDismissedAds()->get(['id'])->pluck('id');
        //here we return all ads (so not just the ads that are belonging to the user, but all
        // return all ads from the db, except the ones that were starred or dismissed by the current user
        return Ad::whereNotIn('id', $allStarredAndDismissedAdIds);//->get() is missing here, put it there later!
    }

    public function filters()
    {
        return $this->hasMany(Filter::class, 'user_id');
    }

    public function getSavedFilters()
    {
        return $this->filters()->get();
    }



}
