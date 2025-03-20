<?php

namespace Modules\Ranks\App\Http\Controllers\Api;

use Modules\Ranks\Entities\Rank;
use Illuminate\Support\Facades\Storage;
use Modules\Ranks\Policies\RanksPolicy;
use Modules\Ranks\Transformers\RanksResource;

class RanksController extends \Lynx\Base\Api
{
    protected $entity           = Rank::class;
    protected $resourcesJson    = RanksResource::class;
    protected $policy           = RanksPolicy::class;
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
        // $data = [
        //     'user_id' => auth('api')->id(),
        // ];
        // $file = lynx()->uploadFile('file', 'test');
        // if (!empty($file)) {
        //     $data['file'] = $file;
        // }
        // return $data;
        return [];
    }

    /**
     * @param $id integer if you want to use in update rules
     * @param string $type (store,update)
     * @return array by (store,update) type using $type variable
     */
    public function rules(string $type, mixed $id = null): array
    {
        return $type == 'store' ? [] : [];
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
        // $data['title'] = 'replace data';
        return $data;
    }

    /**
     * this method can use or append store data
     * @return array
     */
    public function afterStore($entity): void
    {
        // dd($entity->id);
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
        return $entity;
    }

    /**
     * this method use or append data when Show data
     * @return array
     */
    public function afterShow($entity): Object
    {
        return new RanksResource($entity);
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

    public function nextRank()
    {
        $user = auth('api')->user();
        $current_rank =  $user->rank;
        $next_rank = Rank::where('id', $current_rank->id + 1)->first();
        if ($next_rank) {
            return lynx()->data([
                'current_rank'    => $current_rank->name,
                'next_rank'       => $next_rank->name,
                'left_required'   => $next_rank->left_volume,
                'left_current'    => $user->left_leg_cv,
                'right_required'  => $next_rank->right_volume,
                'right_current'   => $user->right_leg_cv,
                'direct_required' => $next_rank->direct_referrals,
                'current_direct'  => $user->referrals()->count()
            ])->message('next rank criteria get successfuly')->response();
        }
        return lynx()->message('no next rank')->response();
    }

    public function dowmlineRanksDetails()
    {
        $user = auth('api')->user();
        $referrals = $user->referrals;
        $data = [];

        $ranks = [
            'Executive'          => 0,
            'Jade'               => 0,
            'Pearl'              => 0,
            'Sapphire'           => 0,
            'Ruby'               => 0,
            'Emerald'            => 0,
            'Diamond'            => 0,
            'Blue_Diamond'       => 0,
            'Black_Diamond'      => 0,
            'Crown'              => 0,
            'Presidential_Crown' => 0
        ];
        $data = [
            'Executive'          =>  ['left' => 0, 'right' => 0],
            'Jade'               =>  ['left' => 0, 'right' => 0],
            'Pearl'              =>  ['left' => 0, 'right' => 0],
            'Sapphire'           =>  ['left' => 0, 'right' => 0],
            'Ruby'               =>  ['left' => 0, 'right' => 0],
            'Emerald'            =>  ['left' => 0, 'right' => 0],
            'Diamond'            =>  ['left' => 0, 'right' => 0],
            'Blue_Diamond'       =>  ['left' => 0, 'right' => 0],
            'Black_Diamond'      =>  ['left' => 0, 'right' => 0],
            'Crown'              =>  ['left' => 0, 'right' => 0],
            'Presidential_Crown' =>  ['left' => 0, 'right' => 0],
        ];
        foreach ($referrals as $referral) {
            if ($referral->rank && array_key_exists($referral->rank->name, $ranks)) {
                $ranks[$referral->rank->name]++;
            }
        }
        // return $ranks;

        $data = [
            'Executive' =>  ['left' => 0, 'right' => 0,]
        ];

        // Prepare the final structure  
        $data['LEFT'] = [];  // Assuming some logic to determine left/right  
        $data['RIGHT'] = []; // Assuming some logic to determine left/right  


        $data['RIGHT']['Executive'] = $ranks['Executive'];
        $data['RIGHT']['Jade'] = $ranks['Jade'];
        $data['RIGHT']['Pearl'] = $ranks['Pearl'];
        $data['RIGHT']['Sapphire'] = $ranks['Sapphire'];
        $data['RIGHT']['Ruby'] = $ranks['Ruby'];
        $data['RIGHT']['Emerald'] = $ranks['Emerald'];
        $data['RIGHT']['Diamond'] = $ranks['Diamond'];
        $data['RIGHT']['Blue_Diamond'] = $ranks['Blue_Diamond'];
        $data['RIGHT']['Black_Diamond'] = $ranks['Black_Diamond'];
        $data['RIGHT']['Crown'] = $ranks['Crown'];
        $data['RIGHT']['Presidential_Crown'] = $ranks['Presidential_Crown'];

        // Add any logic you need to populate LEFT array, could be similar  
        // For now, initial data shows all zeros, so you can adopt same structure  

        // Return the final data  
        return $data;
    }
}
