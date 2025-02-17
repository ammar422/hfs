<?php

namespace Modules\Wallets\App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use Modules\Wallets\Entities\CommissionWalletTransaction;
use Modules\Wallets\Policies\CommissionWalletTransactionPolicy;
use Modules\Wallets\Transformers\CommissionWalletTransactionResource;

class CommissionWalletTransactionController extends \Lynx\Base\Api
{
    protected $entity               = CommissionWalletTransaction::class;
    protected $resourcesJson        = CommissionWalletTransactionResource::class;
    protected $policy               = CommissionWalletTransactionPolicy::class;
    protected $guard                = 'api';
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
        return $entity->where('user_id', auth('api')->id());
    }

    /**
     * this method append data when store or update data
     * @return array
     */
    public function append(): array
    {
        $data = [
            'user_id' => auth('api')->id(),
            'commission_wallet_id' => auth('api')->user()->commissionWallet->id,

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
            'amount'            => 'required|numeric|min:0.01',
            'transaction_type'  => 'required|in:credit,debit,cach,vodafon_cash,to_token_wallet,visa,commission_transaction',
        ] : [];
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
        $data['transaction_type'] == 'commission_transaction' ? $data['token_wallet_id'] = auth('api')->user()->tokenWallet->id : null;
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
        return $entity->where('title', '=', null);
    }

    /**
     * this method use or append data when Show data
     * @return array
     */
    public function afterShow($entity): Object
    {
        return new CommissionWalletTransactionResource($entity);
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
