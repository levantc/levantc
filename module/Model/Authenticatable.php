<?php

namespace Eta\Core\Module\Model;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
abstract class Authenticatable extends User
{
    use Notifiable, TwoFactorAuthenticatable;

    //
}
