<?php 

namespace App\Repository\UserRepository;

use App\Repository\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Models\Ad;

class UserRepository implements UserRepositoryInterface {

    /**
     * returnnig all starred and dismissed ads from ads table, together with their value in the ad_user pivot table
     * This can't return non-processed ads, since these by nature do not belong to the user.
     * Non processed ad will belong to the user when it is starred or dismissed, but by that time it won't 
     * NP ad any more, it will become starred or dismissed ad.
     *
     * @param User $user
     * @return void
     */
    public function allStarredAndDismissedAds(User $user)
    {
        return $user->belongsToMany(Ad::class)->withPivot('dismissed');
    }

    public function duplicateAds(User $user)
	{
		return $user->belongsToMany(Ad::class, 'duplicates');
	}

    /**
     * starredAds will return all ads that are starred by the user. In db, if an ad is starred,
     * then its value in the pivot dismissed column is false.
     * So here we are returning all ads from ads table, that have false in the dissmissed 
     * column in the ad_user pivot table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function starredAds(User $user)
    {
        return $this->allStarredAndDismissedAds($user)->wherePivot('dismissed', '=', false);
    }

    /**
     * dismissedAds will return all ads that are dismissed by the user. In db, if an ad is dismissed,
     * then its value in the pivot dismissed column is true.
     * returning all ads from ads table, that have true in the dissmissed column in the ad_user pivot table.
     *
     * @return mixed
     */
    public function dismissedAds(User $user)
    {
        return $this->allStarredAndDismissedAds($user)->wherePivot('dismissed', '=', true);
    }

    /**
     * notProcessedAds will return all the ads, that are not dismissed or starred by th user.
     * The steps for this are: 
     * get all dismissed ad ids.
     * return all ads (so not just the ads that are belonging to the user, but all
     * return all ads from the db, except the ones that were starred or dismissed by the current user
     *
     * @return mixed
     */
    public function notProcessedAds(User $user)
    {
        $allStarredAndDismissedAdIds = $this->allStarredAndDismissedAds($user)->get(['id'])->pluck('id');
        return Ad::whereNotIn('id', $allStarredAndDismissedAdIds);//->get() is missing here, put it there later!
    }
}