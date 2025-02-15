<?php

namespace Modules\Users\App\Http\Controllers;

use Illuminate\Support\Str;
use Modules\Users\App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Users\App\Resources\UserResource;
use Modules\Users\App\Http\Requests\LoginRequest;
use Modules\Users\App\Jobs\SendVerificationCodeJob;
use Modules\Users\App\Http\Requests\RegisterRequest;
use Modules\Users\App\Http\Requests\VerifyEmailRequest;
use Modules\Users\App\Http\Requests\UpdateProfileRequest;

class UsersAuthController extends Controller
{

    /**
     * @param RegisterRequest $request
     *
     * @return object
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = is_string($request->password) ? bcrypt($request->password) : null;
        $data['account_type'] = 'user';
        $data['sponsor_id'] = User::where('id_code', $request->sponsor_id)->first()->id;
        $user = User::create($data);
        if ($user instanceof User) {
            $token = auth('api')->login($user);

            // SendVerificationCodeJob::dispatch($user);
            $code = rand(100000, 999999);
            $user->verification_code = $code;
            $user->account_status = 'pending';
            $user->save();


            if (!$token = auth('api')->attempt([
                'email'         => $data['email'],
                'password'      => $request->password,
                'account_type'  => 'user'
            ], true)) {
                return $this->respondInvaliedCredentials();
            }
            return $this->respondWithToken($token, $user, __('users::auth.register'));
        }
    }

    public function sponsorData($id)
    {
        $sponsor = User::where('id_code', $id)->first();
        dd($sponsor);
        !empty($sponsor) ?   $this->respondWithUserData($sponsor) : lynx()->message('this user not found')->response();
    }


    public function editProfile(UpdateProfileRequest $request)
    {
        $data = $request->validated();
        $user = auth('api')->user();
        if ($request->hasFile('photo')) {
            $path =  $this->imageProccessing($user, $request->file('photo'));
            $data['photo'] = $path;
        }
        $user->update($data);
        return $this->respondWithUserData($user, __('users::auth.data_udated'));
    }

    private function imageProccessing(User $user, $newPhoto)
    {
        if (!empty($user->photo)) {
            $oldPhoto = Str::after($user->photo, env('APP_URL'));
            Storage::delete($oldPhoto);
        }
        !empty($newPhoto) ? $path = $newPhoto->store('users/' . $user->id) : $path = $user->photo;
        return $path;
    }
    /**
     * @param VerifyEmailRequest $request
     *
     * @return object
     */
    public function verifyEmail(VerifyEmailRequest $request)
    {
        $user = auth('api')->user();


        if (!$user || $user->verification_code != $request->verification_code) {
            return lynx()
                ->status(400)
                ->message(__('users::auth.Invalid_code'))
                ->response();
        }
        $user->email_verified_at = now();
        $user->verification_code  = null;
        $user->account_status = 'active';
        $user->save();

        return lynx()
            ->message(__('users::auth.verified_done'))
            ->response();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     *
     * @return object
     */
    public function login(LoginRequest $request)
    {

        $credentials = [];
        $credentials['account_type'] = 'user';
        $credentials['password'] = $request->password;

        if (filter_var($request->account, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $request->account;
        } else {
            $credentials['mobile'] = $request->account;
        }
        // return $credentials ;
        $token = auth('api')->attempt($credentials, request('remember_me'));
        if (!$token) {
            return $this->respondInvaliedCredentials();
        }

        $user = auth('api')->user();

        return $this->respondWithToken($token, $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return object
     */
    public function me()
    {
        return $this->respondWithUserData(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return object
     */
    public function logout()
    {
        auth('api')->logout();
        return lynx()
            ->message(__('users::auth.loged_out'))
            ->response();
    }

    /**
     * Refresh a token.
     *@return object
     */
    public function refresh()
    {
        $user = auth('api')->user();
        return $this->respondWithToken(auth('api')->refresh(), $user);
    }

    /**
     * Get the token array structure.
     *
     * @param  mixed $token
     * @param  User|null $user
     * @param  string $message
     * @return object
     */
    protected function respondWithToken($token, $user, $message = null)
    {
        $message = $message ?? __('users::auth.login_success');
        return lynx()
            ->data([
                'token' => $token,
                'user' => new UserResource($user),
            ])
            ->message($message)
            ->response();
    }
    /**
     * Get the user data in array structure.
     *
     * @param  User|null $user
     * @param  string $message
     * @return object
     */
    protected function respondWithUserData($user, $message = null)
    {
        $message = $message ?? __('users::auth.data_get');
        return lynx()
            ->data([
                'user' => new UserResource($user),
            ])
            ->message($message)
            ->response();
    }

    /**
     * @return object
     */
    protected function respondInvaliedCredentials()
    {
        return lynx()
            ->status(404)
            ->message(__('users::auth.login_failed'))
            ->response();
    }

    /**
     * @param mixed $errors
     *
     * @return object
     */
    protected function respondValidationError($errors)
    {
        return lynx()
            ->status(422)
            ->message($errors)
            ->response();
    }
}
