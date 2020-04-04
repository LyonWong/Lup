<?php

namespace App\Services\Notification\SMS;

interface SMS
{
    public function sendSignCode($phoneNumber, $code);
}