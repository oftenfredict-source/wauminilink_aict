<?php

namespace App\Notifications\Backup;

use Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification as SpatieBackupWasSuccessfulNotification;

class BackupWasSuccessfulNotification extends SpatieBackupWasSuccessfulNotification
{
    public function toSms($notifiable): string
    {
        return "Shalom! Backup ya mfumo wa WauminiLink (AIC) imekamilika kikamilifu kwenye Google Drive leo tarehe " . date('d/m/Y H:i') . ".";
    }
}
