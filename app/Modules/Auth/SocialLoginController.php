<?php

namespace App\Modules\Auth;

use App\Facades\ApiResponse;
use App\Http\Controllers\Controller;
use App\Modules\Auth\Requests\SocialLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication endpoints"
 * )
 */
class SocialLoginController extends Controller
{
    public function __construct(public AuthServices $authServices, private Socialite $socialite)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/auth/{provider}/callback",
     *     summary="Handle provider callback",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="provider",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", enum={"google", "facebook", "github"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Provider not supported",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Provider not supported"),
     *             @OA\Property(property="code", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to process this action, please try again."),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function signIn(SocialLoginRequest $request, $provider)
    {
        try {
            if (!in_array($provider, config('services.socialite_providers'))) {
                return ApiResponse::message(
                    'Provider not supported',
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $this->authServices->handleSocialLogin($request, $provider);

        } catch (InvalidStateException $e) {
            return ApiResponse::message('Invalid state. Please try again.', Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return ApiResponse::serverError($e->getMessage());
        }
    }

}
