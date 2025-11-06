<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Requests\Api\V2\Auth\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use Throwable;

class ForgotPasswordController extends JsonApiController
{
    /**
     * Handle the incoming password reset request.
     * Returns JSON:API compliant response.
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $response = $this->sendPasswordResetLink($request->only('email'));

            return $this->handlePasswordResetResponse($response);
        } catch (Throwable $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * Send password reset link to the user.
     *
     * @param array $credentials
     * @return string
     */
    protected function sendPasswordResetLink(array $credentials): string
    {
        return Password::sendResetLink($credentials);
    }

    /**
     * Handle the response from the password broker.
     *
     * @param string $response
     * @return JsonResponse
     */
    protected function handlePasswordResetResponse(string $response): JsonResponse
    {
        return match ($response) {
            Password::RESET_LINK_SENT => response()->json([], 204),
            Password::INVALID_USER => $this->reply()->errors([
                Error::fromArray([
                    'title' => 'Bad Request',
                    'detail' => trans($response),
                    'status' => '400',
                    'source' => [
                        'pointer' => '/data/attributes/email'
                    ],
                    'meta' => [
                        'failed' => [
                            'rule' => 'exists'
                        ]
                    ]
                ])
            ]),
            default => $this->reply()->errors([
                Error::fromArray([
                    'title' => 'Password Reset Failed',
                    'detail' => trans($response),
                    'status' => '400'
                ])
            ])
        };
    }

    /**
     * Handle any exceptions that occur during the password reset process.
     *
     * @param Throwable $exception
     * @return JsonResponse
     */
    protected function handleException(Throwable $exception): JsonResponse
    {
        return $this->reply()->errors([
            Error::fromArray([
                'title' => 'Password Reset Error',
                'detail' => $exception->getMessage(),
                'status' => '500',
            ])
        ]);
    }
}
