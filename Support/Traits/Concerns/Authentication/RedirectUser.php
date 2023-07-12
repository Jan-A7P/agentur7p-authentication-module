<?php

namespace Modules\Authentication\Support\Traits\Concerns\Authentication;

trait RedirectUser
{
    protected function redirectPath()
    {
        if(method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }

    protected function logoutRedirectPath()
    {
        if(method_exists($this, 'logoutRedirectTo')) {
            return $this->logoutRedirectTo();
        }

        return property_exists($this, 'logoutRedirectTo') ? $this->logoutRedirectTo : '/';
    }
}
