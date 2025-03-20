<?php

namespace Modules\Packages\App\Http\Controllers\Api;

use Lynx\Base\Api;
use App\Services\StripeService;
use Modules\Packages\Entities\Package;
use Illuminate\Support\Facades\Storage;
use Modules\Packages\Entities\Subscription;
use Modules\Packages\Policies\SubscriptionPolicy;
use Modules\Packages\Transformers\SubscriptionResource;

class SubscriptionController extends Api
{
    protected $entity           = Subscription::class;
    protected $resourcesJson    = SubscriptionResource::class;
    protected $policy           = SubscriptionPolicy::class;
    protected $guard            = 'api';
    protected $spatieQueryBuilder   = true;
    protected $paginateIndex        = true;
    protected $withTrashed          = false;
    protected $FullJsonInStore      = true;  // TRUE,FALSE
    protected $FullJsonInUpdate     = false;  // TRUE,FALSE
    protected $FullJsonInDestroy    = false;  // TRUE,FALSE


    protected $stripe_service;
    public function __construct(StripeService $stripe_service)
    {
        $this->stripe_service = $stripe_service;
    }

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
            'package_id' => 'required|exists:packages,id',
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
        $package = Package::find(request('package_id'));
        $data['name']           = $package->name;
        $data['cv']             = $package->cv;
        $data['billing_period'] = $package->billing_period;
        return $data;
    }

    /**
     * this method can use or append store data
     * @return array
     */
    public function afterStore($entity): void
    {
        $package = Package::find(request('package_id'));
        $payment = $this->stripe_service->createPaymentIntent($package->price);
        $entity->payment_intent_id = $payment['id'];
        $entity->amount = intval($payment['amount']) / 100;
        $entity->save();
    }


    public function capturePayment()
    {
        return $this->stripe_service->capturePayment(request('paymentIntentId'));
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
        return new SubscriptionResource($entity);
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
