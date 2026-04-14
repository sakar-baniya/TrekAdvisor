<?php

// use Throwable; // Removed as it's unnecessary in this context
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:smoke-test {email? : Recipient email address}', function (?string $email = null) {
    $target = $email ?: (string) config('mail.from.address');

    if (! filled($target)) {
        $this->error('No target email was provided and MAIL_FROM_ADDRESS is empty.');
        return self::FAILURE;
    }

    $this->line('Running mail smoke test...');
    $this->line('Target: ' . $target);
    $this->line('Mailer: ' . config('mail.default'));

    try {
        Mail::raw(
            'TrekAdvisor mail smoke test at ' . now()->toDateTimeString(),
            function ($message) use ($target): void {
                $message->to($target)
                    ->subject('TrekAdvisor Mail Smoke Test');
            }
        );

        $this->info('Mail smoke test sent successfully.');
        return self::SUCCESS;
    } catch (\Throwable $exception) {
        $this->error('Mail smoke test failed.');
        $this->error('Reason: ' . $exception->getMessage());
        return self::FAILURE;
    }
})->purpose('Send a minimal test email and report delivery setup status');
