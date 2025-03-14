<?php

namespace App\Modules\Auth;

use App\Facades\ApiResponse;
use App\Facades\FileHandeler;
use App\Modules\Users\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthServices
{

    public function handleSocialLogin($user,$provider)
    {

        $_user = User::where('email',$user->email)->first();
        if ($_user) {
            $token = $this->generateToken($_user);
            return $this->respondWithToken($_user,$token);
        }

        $userData = $this->prepareUserData($user,$provider);
        $user = User::create($userData);
        $token = $this->generateToken($user);
        return $this->respondWithToken($user,$token);
    }

    /**
     * Generate token for user
     * @param $user
     * @param $email
     * @return string
     */
    public function generateToken($user)
    {
        return $user->createToken('New API Token')->plainTextToken;
    }


    public function prepareUserData ($request,$provider = 'email')
    {
        $password = $request->password ?? Str::random(8);
        $avatar = $this->avatarHandeler($request->avatar, $provider);

        return [
            'name' => $request->name ?? 'new user',
            'email' => $request->email,
            'password' => Hash::make($password),
            'phone' => $request->phone ?? null,
            'verified' => ($provider != 'email'),
            'provider' => $provider,
            'provider_id' => $request->idToken ?? null,
            'avatar' => $avatar ?? 'default.png',
        ];
    }

    public function avatarHandeler($avatar , $provider)
    {
        if ($provider != 'email') {
            return $avatar;
        }

        $avatarName = $avatar ? FileHandeler::storeFile($avatar,'avatars','jpg') : 'avatars/default.jpg';
        return $avatarName;
    }

    /**
     * Get the token array structure.
     * @param  string $token
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function respondWithToken($user,$token,$message = 'logged in successfully')
    {
        return ApiResponse::success([
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => $user
            ]
        ],$message);
    }}
