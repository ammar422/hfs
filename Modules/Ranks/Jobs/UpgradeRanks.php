<?php

namespace Modules\Ranks\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Ranks\Entities\Rank;
use Modules\Users\App\Models\User;

class UpgradeRanks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::whereHas('subscription', function ($query) {
            return $query->where('cv', '!=', 0);
        })
            ->whereHas('rightLegReferrals')
            ->whereHas('leftLegReferrals')
            ->hasBothLegsCv()
            ->where('account_status', 'active')
            ->get();


        foreach ($users as $user) {
            $rank = null;

            // Check for ranks, from lowest to highest  
            if ($user->left_leg_cv >= 100  && $user->right_leg_cv >= 100) {
                $rank = Rank::where('left_volume', '>=', 100)
                    ->where('direct_referrals', 2)
                    ->where('name', 'Executive')->first()->id;
                $user->rank_id = $rank;
            }

            if ($user->left_leg_cv >= 500 && $user->right_leg_cv >= 500) {
                $rank = Rank::where('left_volume', '>=', 500)
                    ->where('direct_referrals', 2)
                    ->where('name', 'Pearl')->first()->id;
                $user->rank_id = $rank;
            }

            if ($user->left_leg_cv >= 1000  && $user->right_leg_cv >= 1000) {
                $rank = Rank::where('left_volume', '>=', 1000)
                    ->where('direct_referrals', 2)
                    ->where('name', 'Sapphire')->first()->id;
                $user->rank_id = $rank;
            }

            if ($user->left_leg_cv >= 8000 && $user->right_leg_cv >= 8000) {
                $rank = Rank::where('left_volume', '>=', 8000)
                    ->where('direct_referrals', 2)
                    ->where('name', 'Ruby')->first()->id;
                $user->rank_id = $rank;
            }

            if (
                $user->left_leg_cv >= 20000  &&
                $user->right_leg_cv >= 20000 &&
                $user->referrals->count() >= 3
            ) {
                $rank = Rank::where('left_volume', '>=', 20000)
                    ->where('direct_referrals', 3)
                    ->where('name', 'Emerald')->first()->id;
                $user->rank_id = $rank;
            }

            if (
                $user->left_leg_cv >= 40000  &&
                $user->right_leg_cv >= 40000 &&
                $user->referrals->count() >= 5
            ) {
                $rank = Rank::where('left_volume', '>=', 40000)
                    ->where('direct_referrals', 5)
                    ->where('name', 'Diamond')->first()->id;
                $user->rank_id = $rank;
            }

            if (
                $user->left_leg_cv >= 80000 &&
                $user->right_leg_cv >= 80000 &&
                $user->referrals->count() >= 6
            ) {
                $rank = Rank::where('left_volume', '>=', 80000)
                    ->where('direct_referrals', 6)
                    ->where('name', 'Blue_Diamond')->first()->id;
                $user->rank_id = $rank;
            }

            if (
                $user->left_leg_cv >= 160000 &&
                $user->right_leg_cv >= 160000 &&
                $user->referrals->count() >= 7
            ) {
                $rank = Rank::where('left_volume', '>=', 160000)
                    ->where('direct_referrals', 7)
                    ->where('name', 'Black_Diamond')->first()->id;
                $user->rank_id = $rank;
            }

            if (
                $user->left_leg_cv >= 300000 &&
                $user->right_leg_cv >= 300000 &&
                $user->referrals->count() >= 8
            ) {
                $rank = Rank::where('left_volume', '>=', 300000)
                    ->where('direct_referrals', 8)
                    ->where('name', 'Crown')->first()->id;
                $user->rank_id = $rank;
            }

            if (
                $user->left_leg_cv >= 500000 && $user->right_leg_cv >= 500000 &&
                $user->referrals->count() >= 10
            ) {
                $rank = Rank::where('left_volume', '>=', 500000)
                    ->where('direct_referrals', 10)
                    ->where('name', 'Presidential_Crown')->first()->id;
                $user->rank_id = $rank;
            }
            $user->save();
        }
    }
}
