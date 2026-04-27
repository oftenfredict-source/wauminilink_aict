<?php

namespace App\Notifications\Backup;

use Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification as SpatieBackupHasFailedNotification;

class BackupHasFailedNotification extends SpatieBackupHasFailedNotification
{
    public function via(): array
    {
        return array_merge(parent::via(), ['sms']);
    }

    public function toSms($notifiable): string
    {
        return "Tahadhari! Backup ya mfumo wa WauminiLink (AIC) IMEFELI leo tarehe " . date('d/m/Y H:i') . ". Tafadhali kagua mfumo mara moja.";
    }
}
