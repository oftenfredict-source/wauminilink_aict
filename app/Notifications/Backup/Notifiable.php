<?php

namespace App\Notifications\Backup;

use Spatie\Backup\Notifications\Notifiable as SpatieNotifiable;

class Notifiable extends SpatieNotifiable
{
    /**
     * Route notifications for the SMS channel.
     *
     * @return string|null
     */
    public function routeNotificationForSms()
    {
        return config('backup.notifications.sms.to');
    }

    /**
     * Get the phone number for the SMS channel.
     * This is required by App\Notifications\Channels\SmsChannel.
     *
     * @return string|null
     */
    public function __get($key)
    {
        if ($key === 'phone_number') {
            return $this->routeNotificationForSms();
        }

        return null;
    }
}
