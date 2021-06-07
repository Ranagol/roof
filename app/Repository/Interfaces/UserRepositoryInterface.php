<?php 

namespace App\Repository\Interfaces;

use app\Models\User;

interface UserRepositoryInterface {
    public function allStarredAndDismissedAds(User $user);
    public function duplicateAds(User $user);
    public function starredAds(User $user);
    public function dismissedAds(User $user);
    public function notProcessedAds(User $user);
}

