<?php

namespace Levantc\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

abstract class Authenticatable extends User
{
    use Notifiable, TwoFactorAuthenticatable;

    //
}
