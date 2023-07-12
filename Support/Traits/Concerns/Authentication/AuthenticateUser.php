<?php

namespace Modules\Authentication\Support\Traits\Concerns\Authentication;

use Modules\Authentication\Http\Requests\LoginRequest;
use App\Support\ResponseCode;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

trait AuthenticateUser
{
    use ThrottleLogins, RedirectUser;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(LoginRequest $request)
    {
        return $this->doLogin($request);
    }

    public function doLogin($request)
    {
        if(method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)){
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if($this->attemptLogin($request)){

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        return $this->doLogout($request);
    }

    public function doLogout($request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], ResponseCode::HTTP_NO_CONTENT)
            : redirect()->intended($this->logoutRedirectPath());
    }

    protected function authenticated($request, $user)
    {
        event('auth.user.loggedin', $user);

//        Auth::logoutOtherDevices($request->get('password'));

//        return redirect()->intended();
    }

    protected function fireLockoutEvent($request)
    {
        event(new Lockout($request));
    }

    protected function attemptLogin($request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->boolean('remember')
        );
    }

    protected function sendLoginResponse($request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], ResponseCode::HTTP_NO_CONTENT)
            : redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse($request)
    {
        throw ValidationException::withMessages([
            $this->username() => [
                trans('auth.failed')
            ]
        ]);
    }

    protected function credentials($request)
    {
        return $request->only($this->username(), 'password');
    }

    protected function username()
    {
        return 'email';
    }

    protected function guard()
    {
        return Auth::guard();
    }

}
