<?php

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Support\Traits\Concerns\Authentication\AuthenticateUser;

class AuthenticationController extends Controller
{
    use AuthenticateUser;
    public function index()
    {
        return view('authentication::login');
    }
}
