<?php

namespace Modules\Commissions\App\Http\Controllers\Api;

use Modules\Users\App\Models\User;
use Illuminate\Support\Facades\Storage;
use Modules\Commissions\Entities\Commission;
use Modules\Wallets\Entities\CommissionWallet;
use Modules\Commissions\Policies\CommissionPolicy;
use Modules\Commissions\Transformers\CommissionResource;

class CommissionController extends \Lynx\Base\Api
{
    protected $entity           = Commission::class;
    protected $resourcesJson    = CommissionResource::class;
    protected $policy           = CommissionPolicy::class;
    protected $guard            = 'api';
    protected $spatieQueryBuilder   = true;
    protected $paginateIndex        = true;
    protected $withTrashed          = false;
    protected $FullJsonInStore      = false;  // TRUE,FALSE
    protected $FullJsonInUpdate     = false;  // TRUE,FALSE
    protected $FullJsonInDestroy    = false;  // TRUE,FALSE

    /**
     * can handel custom query when retrive data on index,indexGuest
     * @param $entity model
     * @return query by Model , Entity
     */
    public function query($entity): Object
    {
        return $entity;
    }

    /**
     * this method append data when store or update data
     * @return array
     */
    public function append(): array
    {
        $referral = User::where('placement', 'tank')
            ->where('account_type', 'user')
            ->where('account_status', 'active')
            ->find(request('referral_id'));
        $user = auth('api')->user();
        $data = [
            'user_id' => $user->id,
            'amount' => $referral->subscription->cv * 20 / 100,
            'paid_at' => now(),
        ];
        // $file = lynx()->uploadFile('file', 'test');
        // if (!empty($file)) {
        //     $data['file'] = $file;
        // }
        return $data;
        // return [];
    }

    /**
     * @param $id integer if you want to use in update rules
     * @param string $type (store,update)
     * @return array by (store,update) type using $type variable
     */
    public function rules(string $type, mixed $id = null): array
    {
        return $type == 'store' ? [
            'referral_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    // Attempt to find the referral user based on the criteria  
                    $referral = User::where('placement', 'tank')
                        ->where('account_type', 'user')
                        ->where('account_status', 'active')
                        ->find($value);

                    // Check if the referral is null  
                    if (is_null($referral)) {
                        $fail('This user is not your referral.');  // User does not exist or does not meet criteria  
                        return;  // Return early to avoid further checks  
                    }

                    // Check if the user belongs to the current authenticated user (sponsor)  
                    if ($referral->sponsor_id === null || $referral->sponsor_id !== auth()->id()) {
                        $fail('This user is not your referral.');  // Not linked to the current authenticated user  
                    }
                },
            ],
            'leg' => 'required|in:left,right',
        ]   : [];
    }

    /**
     * this method can set your attribute names with validation rules
     * @return array
     */
    public function niceName()
    {
        return [];
    }


    /*
     * this method use or append or change data before store
     * @return array
     */
    public function beforeStore(array $data): array
    {

        return $data;
    }

    /**
     * this method can use or append store data
     * @return array
     */
    public function afterStore($entity): void
    {
        $referral = User::where('placement', 'tank')
            ->where('account_type', 'user')
            ->where('account_status', 'active')
            ->find(request('referral_id'));

        $referral->placement = 'tree';

        // $user = auth('api')->user();
        $sponsor = User::find(auth('api')->id());

        $sponsor->cv += $referral->subscription->cv;

        request('leg') == 'left' ? $sponsor->left_leg_cv += $referral->subscription->cv : $sponsor->right_leg_cv += $referral->subscription->cv;
        // request('leg') == 'left' ? $sponsor->left_leg_id = $referral->id : $sponsor->right_leg_id = $referral->id;
        $this->placeUser($referral, $sponsor);

        $commission = $referral->subscription->cv * 0.2;
        $user_commission_wallet = CommissionWallet::where('user_id', $sponsor->id)->first();
        $balance = $user_commission_wallet->balance;
        $user_commission_wallet->update([
            'balance' => $balance + $commission
        ]);
        $referral->save();
        $sponsor->save();
    }

    protected function placeUser($referral,  $sponsor)
    {
        // Determine where to place the referral
        $legType = request('leg'); // 'left' or 'right'
        // If sponsor's direct leg is empty, place here
        // dd( $legType );
        if (!$sponsor->{$legType . '_leg_id'}) {
            $sponsor->update(["{$legType}_leg_id" => $referral->id]);
        } else {
            // Find the last node in the selected leg
            $lastNode = $this->findLastNode($sponsor, $legType);
            // dd($lastNode);
            // Place under the last node's same leg
            $lastNode->update(["{$legType}_leg_id" => $referral->id]);
        }
    }
    private function findLastNode($sponsor,  $legType): User
    {

        $current = $sponsor;
        while (true) {
            $legType == 'left' ? $next_id =  $current->left_leg_id : $next_id =  $current->right_leg_id;
            $next = User::where('placement', 'tree')
            ->where('account_type', 'user')
            ->where('account_status', 'active')
            ->find($next_id);
            if (!$next) {
                return $current; // Found the last node
            }
            $current = $next;
        }
    }
    /**
     * this method use or append or delete data beforeUpdate
     * @return array
     */
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
