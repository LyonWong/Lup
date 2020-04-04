<?php

namespace App\Services\Notification\SMS;

use Illuminate\Support\Facades\Log;

class MockSMS implements SMS
{
    public function sendSignCode($phoneNumber, $code)
    {
        $message = "SendSignCode:$code@$phoneNumber";
        Log::info($message);
    }
}