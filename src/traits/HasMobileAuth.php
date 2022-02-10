<?php

namespace Mafhhend\LaravelMobileAuth\traits;


trait HasMobileAuth
{
    // initialize+<trait-name>, every where you use this trait this method will run automatically
    public function initializeHasMobileAuth()
    {
        $this->fillable[]='phone';
        $this->fillable[]='attempts_left';
        $this->fillable[]='must_login_with_otp';
    }
}
