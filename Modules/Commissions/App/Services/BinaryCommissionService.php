<?php
namespace Modules\Commissions\App\Services;

use Modules\Users\App\Models\User;
use Modules\Commissions\Entities\Commission;


class BinaryCommissionService {
    protected $legService;

    public function __construct(LegService $legService) {
        $this->legService = $legService;
    }

    public function calculateBinaryCommissions() {
        // Auto-place users from the tank
        $this->autoPlaceUsers();

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

    // Auto-place users from the tank into the sponsor's stronger leg
    private function autoPlaceUsers() {
        $tankUsers = User::where('placement', 'tank')->get();

        foreach ($tankUsers as $user) {
            $sponsor = $user->sponsor;
            if (!$sponsor) continue;

            // Determine stronger leg
            $legType = ($sponsor->left_leg_cv >= $sponsor->right_leg_cv) ? 'left' : 'right';

            // Find the last node in the leg
            $lastNode = $this->legService->findLastNode($sponsor, $legType);

            // Place the user under the last node
            $lastNode->update(["{$legType}_leg_id" => $user->id]);

            // Propagate CV up the hierarchy
            $this->updateLegCV($user, $legType);

            // Update user placement
            $user->update(['placement' => $legType]);
        }
    }

    // Update CV for all ancestors in the leg
    private function updateLegCV(User $user, string $legType) {
        $current = $user->sponsor;
        while ($current) {
            $current->increment("{$legType}_leg_cv", $user->cv);
            $current = $current->sponsor;
        }
    }
}