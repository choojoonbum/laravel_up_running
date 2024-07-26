<?php

namespace App\container;

use Illuminate\Mail\Mailer;

class UserMailer
{
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function welcome($user)
    {
        return $this->mailer->mail($user->email, 'welcome');
    }

}
