<?php

namespace Modules\Commissions\App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use Modules\Users\App\Models\User;
use Illuminate\Support\Facades\Storage;
use Modules\Commissions\Entities\Commission;
use Modules\Wallets\Entities\CommissionWallet;
use Modules\Commissions\App\Services\LegService;
use Modules\Commissions\Policies\CommissionPolicy;
use Modules\Commissions\Transformers\CommissionResource;
use Modules\Commissions\App\Services\BinaryCommissionService;

class CommissionController extends \Lynx\Base\Api
{
    protected $entity = Commission::class;
    protected $resourcesJson = CommissionResource::class;
    protected $policy = CommissionPolicy::class;
    protected $guard = 'api';
    protected $spatieQueryBuilder = true;
    protected $paginateIndex = true;
    protected $withTrashed = false;
    protected $FullJsonInStore = false;
    protected $FullJsonInUpdate = false;
    protected $FullJsonInDestroy = false;

    protected BinaryCommissionService $binaryCommissionService;
    protected LegService $legService;

    public function __construct(
        BinaryCommissionService $binaryCommissionService,
        LegService $legService
    ) {
        parent::__construct();
        $this->binaryCommissionService = $binaryCommissionService;
        $this->legService = $legService;
    }

    public function query($entity): object
    {
        return $entity->with(['user', 'wallet']);
    }

    public function append(): array
    {
        $referral = User::where('placement', 'tank')
            ->where('account_type', 'user')
            ->where('account_status', 'active')
            ->findOrFail(request('referral_id'));

        return [
            'user_id' => auth()->id(),
            'amount' => $referral->subscription->cv * 0.2,
            'paid_at' => now(),
        ];
    }

    public function rules(string $type, mixed $id = null): array
    {
        if ($type !== 'store') {
            return [];
        }

        return [
            'referral_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $referral = User::where('placement', 'tank')
                        ->where('account_type', 'user')
                        ->where('account_status', 'active')
                        ->find($value);
                    // dd($referral->sponsor_id !== auth()->id());
                    if (!$referral || $referral->sponsor_id !== auth()->id()) {
                        $fail('Invalid referral selection');
                    }
                },
            ],
            'leg' => 'required|in:left,right',
        ];
    }

    public function afterStore($commission): void
    {
        $referral = User::findOrFail(request('referral_id'));
        $sponsor = auth()->user();

        $this->placeReferral($referral, $sponsor);
        $this->processCommissions($referral, $sponsor);
    }

    private function placeReferral(User $referral, User $sponsor): void
    {
        // $cv = $referral->subscription->cv;
        $legType = request('leg');
        // $sponsor->cv += $cv;
        // $sponsor->save();

        $referral->update([
            'placement' => 'tree',
            // 'sponsor_id' => $sponsor->id,
            'leg_type' => $legType
        ]);

        // dd($sponsor->{"{$legType}_leg_id"});
        if (!$sponsor->{"{$legType}_leg_id"}) {
            $sponsor->update(["{$legType}_leg_id" => $referral->id]);
            $referral->update(['upline_id' => $sponsor->id]);
        } else {
            $lastNode = $this->legService->findLastNode($sponsor, $legType);
            $lastNode->update(["{$legType}_leg_id" => $referral->id]);
            $referral->update(['upline_id' => $lastNode->id]);
        }

        $this->updateLegCV($referral, $legType);
    }

    private function updateLegCV(User $referral, string $legType): void
    {
        $cv = $referral->subscription->cv;
        // $leg = $referral->leg_type;
        $current = $referral->upline;
        while ($current) {
            // dd($current);
            // $current->update(["{$legType}_leg_cv" => $current->{"{$legType}_leg_cv"} + $cv]);
            $current->id == 1 ? $legType = $current->downline->leg_type : $legType = request('leg');
            // dd($legType);
            // dd("{$legType}_leg_cv");
            $current->update(["{$legType}_leg_cv" => $current->{"{$legType}_leg_cv"} + $cv]);
            // $current->update(["{$leg}_leg_cv" => $current->{"{$leg}_leg_cv"} + $cv]);
            $current->update(['cv' => $current->cv + $cv]);
            $current = $current->upline;
        }
    }

    private function processCommissions(User $referral, User $sponsor): void
    {
        // Direct commission
        $directCommission = $referral->subscription->cv * 0.2;
        $currentBalance = CommissionWallet::where('user_id', $sponsor->id)->value('balance');
        $newBalance = $currentBalance + $directCommission;

        CommissionWallet::updateOrCreate(
            ['user_id' => $sponsor->id],
            ['balance' => $newBalance]
        );
        // CommissionWallet::updateOrCreate(
        //     ['user_id' => $sponsor->id],
        //     ['balance' => DB::raw("balance + $directCommission")]
        // );

        // Binary commissions for uplines
        $this->binaryCommissionService->calculateUplineCommissions($referral);
    }
    public function beforeUpdate($entity): void
    {
        if (!empty($entity->file)) {
            Storage::delete($entity->file);
        }
    }

    /**
     * this method use or append data after Update
     * @return array
     */
    public function afterUpdate($entity): void
    {
        // dd($entity->id);
    }

    /**
     * this method use or append data when Show data
     * @return array
     */
    public function beforeShow($entity): Object
    {
        return $entity->where('title', '=', null);
    }

    /**
     * this method use or append data when Show data
     * @return array
     */
    public function afterShow($entity): Object
    {
        return new CommissionResource($entity);
    }

    /**
     * you can do something in this method before delete record
     * @param object $entity
     * @return void
     */
    public function beforeDestroy($entity): void
    {
        if (!empty($entity->file)) {
            Storage::delete($entity->file);
        }
    }

    /**
     * you can do something in this method after delete record
     * @param object $entity
     * @return void
     */
    public function afterDestroy($entity): void
    {
        // do something
        // $entity->file
    }
}
