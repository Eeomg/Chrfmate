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
     * @OA\Post(
     *     path="/api/auth/google",
     *     summary="social login",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(property="name", type="string", format="sting", example="user"),
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="avatar", type="string", format="binary"),
     *                 @OA\Property(property="idToken", type="string", format="string", example=""),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation Error"),
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}})
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
