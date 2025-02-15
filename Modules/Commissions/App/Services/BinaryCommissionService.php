<?php

namespace Modules\Commissions\App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Users\App\Models\User;
use Modules\Commissions\Entities\Commission;
use Modules\Wallets\Entities\CommissionWallet;

class BinaryCommissionService
{
    public function calculateUplineCommissions(User $referral): void
    {
        $currentUpline = $referral->upline;
        $addedCV = $referral->subscription->cv;

        while ($currentUpline) {
            if ($currentUpline->isEligibleForBinary()) {
                $commissionAmount = $currentUpline->getWeakerLegValue() * 0.2;

                // Create commission record
                Commission::create([
                    'user_id' => $currentUpline->id,
                    'amount' => $commissionAmount,
                    'type' => 'binary',
                    'paid_at' => now()
                ]);

                // Update wallet
                CommissionWallet::updateOrCreate(
                    ['user_id' => $currentUpline->id],
                    ['balance' => DB::raw("balance + $commissionAmount")]
                );

                // Deduct CV from legs
                $weakerValue = $currentUpline->getWeakerLegValue();
                $currentUpline->decrement('left_leg_cv', $weakerValue);
                $currentUpline->decrement('right_leg_cv', $weakerValue);
            }

            $currentUpline = $currentUpline->upline;
        }
    }
    public function calculateBinaryCommissions()
    {
        // Auto-place users from the tank
        // $this->autoPlaceUsers();

        // Process eligible users
        $eligibleUsers = User::whereNotNull('left_leg_id')
            ->whereNotNull('right_leg_id')
            ->get();

        foreach ($eligibleUsers as $user) {
            $leftCV = $user->left_leg_cv;
            $rightCV = $user->right_leg_cv;
            $weakerCV = min($leftCV, $rightCV);

            if ($weakerCV > 0) {
                // Create binary commission
                Commission::create([
                    'user_id' => $user->id,
                    'amount' => $weakerCV * 0.20,
                    'type' => 'binary',
                    'paid_at' => now(),
                ]);

                //add binary commission to commission wallet
                $commission = $weakerCV * 0.2;
                $user_commission_wallet = CommissionWallet::where('user_id',  $user->id,)->first();
                $balance = $user_commission_wallet->balance;
                $user_commission_wallet->update([
                    'balance' => $balance + $commission
                ]);
                $user->save();

                // Deduct weaker leg from both legs
                $user->decrement('left_leg_cv', $weakerCV);
                $user->decrement('right_leg_cv', $weakerCV);
            }
        }

        // Reset legs for ineligible users
        User::where(function ($query) {
            $query->whereNull('left_leg_id')
                ->orWhereNull('right_leg_id');
        })->each(function ($user) {
            $user->update([
                'left_leg_cv' => $user->left_leg_cv < $user->right_leg_cv ? 0 : $user->left_leg_cv,
                'right_leg_cv' => $user->right_leg_cv < $user->left_leg_cv ? 0 : $user->right_leg_cv,
            ]);
        });
    }

}
