<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Modules\Packages\App\Http\Controllers\Api\PackageController;
use Modules\Packages\App\Http\Controllers\Api\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::apiResource('Packages', PackageController::class)->only('index', 'show');

Route::middleware('auth:api', 'verified')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('subscribe', [SubscriptionController::class, 'store'])->middleware('check.subscripition');
        Route::get('subscribe', [SubscriptionController::class, 'index']);
        Route::delete('subscribe/{user_id}', [SubscriptionController::class, 'destroy']);
        Route::post('/subscribe/capture-payment', [SubscriptionController::class, 'capturePayment']);
    });



    Route::post('/stripe/webhook', function (Request $request) {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = json_decode($payload, true);

            if ($event['type'] === 'payment_intent.succeeded') {
                $paymentIntent = $event['data']['object'];
                Log::info("Payment Successful:", $paymentIntent);
            }

            return response()->json(['message' => 'Webhook received']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook verification failed'], 400);
        }
    });
});
