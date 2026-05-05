<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class DebugMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:debug-mail {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Mail delivery with detailed debug output';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Starting Mail debug for email: {$email}");

        $this->info("\nCurrent Configuration (from Config cache):");
        $this->line("- Mailer: " . Config::get('mail.default'));
        $this->line("- Host: " . Config::get('mail.mailers.smtp.host'));
        $this->line("- Port: " . Config::get('mail.mailers.smtp.port'));
        $this->line("- Username: " . Config::get('mail.mailers.smtp.username'));
        $this->line("- Encryption: " . Config::get('mail.mailers.smtp.encryption'));
        $this->line("- From Address: " . Config::get('mail.from.address'));

        $this->info("\nAttempting to send test email...");

        try {
            Mail::raw('Test email from WauminiLink Backup Debugger', function ($message) use ($email) {
                $message->to($email)
                    ->subject('WauminiLink Debug Email');
            });
            $this->info("Success! Email sent without exceptions.");
        } catch (\Throwable $e) {
            $this->error("Failed to send email!");
            $this->error("Error Message: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . ":" . $e->getLine());

            if (str_contains($e->getMessage(), 'Connection refill') || str_contains($e->getMessage(), 'Connection refused')) {
                $this->alert("TIP: Your server might be blocking port " . Config::get('mail.mailers.smtp.port') . ". Try switching to port 587 with 'tls'.");
            }
        }

        return 0;
    }
}
