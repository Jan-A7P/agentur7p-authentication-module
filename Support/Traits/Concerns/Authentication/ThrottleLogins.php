<?php

namespace Modules\Authentication\Support\Traits\Concerns\Authentication;

use App\Support\ResponseCode;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait ThrottleLogins
{
    protected function hasTooManyLoginAttempts($request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxAttempts()
        );
    }

    protected function incrementLoginAttempts($request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request), $this->decayMinutes() * 60
        );
    }

    protected function sendLockoutResponse($request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw ValidationException::withMessages([
            $this->username() => [
                trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60)
                ])
            ]
        ])->status(ResponseCode::HTTP_TOO_MANY_REQUESTS);
    }

    protected function clearLoginAttempts($request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    protected function throttleKey($request)
    {
        return Str::transliterate(Str::lower($request->input($this->username())).'|'.$request->ip());
    }

    protected function limiter()
    {
        return app(RateLimiter::class);
    }

    public function maxAttempts()
    {
        return property_exists($this, 'maxAttempts') ? $this->maxAttempts : 5;
    }

    public function decayMinutes()
    {
        return property_exists($this, 'decayMinutes') ? $this->decayMinutes : 1;
    }
}
