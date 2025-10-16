<?php

namespace App\Providers;

use App\Models\MailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('mail_settings')) {
            $mail = MailSetting::first();
            if ($mail) {
                $config = [
                    'driver' => $mail->driver,
                    'host' => $mail->host,
                    'port' => $mail->port,
                    'from' => ['address' => $mail->from_address, 'name' => $mail->from_name],
                    'encryption' => $mail->encryption,
                    'username' => $mail->username,
                    'password' => $mail->password,
                    'sendmail' => '/usr/sbin/sendmail -bs',
                    'pretend' => false,
                ];
                Config::set('mail', $config);
            }

        }
    }
}
