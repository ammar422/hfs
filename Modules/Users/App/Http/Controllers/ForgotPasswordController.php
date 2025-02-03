<?php

namespace Modules\Users\App\Http\Controllers;

use Modules\Users\App\Models\User;
use App\Http\Controllers\Controller;
use Modules\Users\App\Events\ResetCodeRequested;
use Modules\Users\App\Http\Requests\ResetPasswordRequest;
use Modules\Users\App\Http\Requests\SendResetCodeRequest;

class ForgotPasswordController extends Controller
{


    /**
     * @param SendResetCodeRequest $request
     * 
     * @return Object
     */
    public function sendResetCode(SendResetCodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            $code = mt_rand(100000, 999999);
            $user->reset_token = $code;
            $user->save();
            ResetCodeRequested::dispatch($user);
            return lynx()
                ->message(__('users::auth.password_code'))
                ->response();
        }
        return lynx()
            ->message(__('users::auth.password_code_faild'))
            ->response();
    }



    /**
     * @param ResetPasswordRequest $request
     * 
     * @return object
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)
            ->where('reset_token', $request->code)
            ->firstOrFail();

        if (is_string($request->password)) {
            $user->password =   bcrypt($request->password);
            $user->reset_token = null;
            $user->save();
            return lynx()
                ->message(__('users::auth.reset_success'))
                ->response();
        } else {
            return lynx()
                ->message(__('users::auth.Invalid_reset_code'))
                ->status(400)
                ->response();
        }
    }
}
