<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\Paginator;
use Illuminate\Session\Events\SessionStarted;
use App\Notifications\Channels\SmsChannel;
use App\Services\SmsService;
use App\Session\DatabaseSessionHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 for pagination links
        Paginator::useBootstrapFive();

        // Handle subdirectory hosting only when explicitly configured.
        // Auto-detection can create incorrect asset URLs on production servers.
        $subdirectory = trim((string) env('APP_SUBDIRECTORY', ''));

        // Set asset URL to include subdirectory
        if ($subdirectory !== '') {
            $subdirectory = '/' . trim($subdirectory, '/');
            $appUrl = rtrim(config('app.url'), '/');
            // Ensure subdirectory doesn't already exist in APP_URL
            if (strpos($appUrl, $subdirectory) === false) {
                $appUrl .= $subdirectory;
            }
            URL::forceRootUrl($appUrl);

            // Also update the public disk URL to include subdirectory
            config(['filesystems.disks.public.url' => $appUrl . '/storage']);
        } else {
            // Let Laravel auto-detect the URL based on the request host.
            // Forcing URL via APP_URL can cause routing / 404 issues on servers if .env is misconfigured.
        }

        // Force HTTPS only when configured.
        // Defaults to APP_URL scheme to avoid breaking HTTP-only deployments.
        $appUrlScheme = parse_url(config('app.url'), PHP_URL_SCHEME);
        $forceHttps = filter_var(
            env('APP_FORCE_HTTPS', $appUrlScheme === 'https'),
            FILTER_VALIDATE_BOOL
        );

        if ($forceHttps) {
            URL::forceScheme('https');
        }

        // Extend the session manager to use our custom database handler
        Session::extend('database', function ($app) {
            $connection = $app['db']->connection($app['config']['session.connection']);
            $table = $app['config']['session.table'];
            $lifetime = $app['config']['session.lifetime'];
            $encrypter = $app->bound('encrypter') ? $app['encrypter'] : null;

            return new DatabaseSessionHandler($connection, $table, $lifetime, $encrypter);
        });

        // Register SMS notification channel
        Notification::extend('sms', function ($app) {
            return new SmsChannel($app->make(SmsService::class));
        });

        // Register Google Drive driver (OAuth refresh token → access token required for every API call)
        try {
            Storage::extend('google', function ($app, $config) {
                $options = [];

                if (!empty($config['teamDriveId'] ?? null)) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }

                if (!empty($config['sharedDriveId'] ?? null)) {
                    $options['sharedDriveId'] = $config['sharedDriveId'];
                }

                $clientId = trim((string) ($config['clientId'] ?? ''));
                $clientSecret = trim((string) ($config['clientSecret'] ?? ''));
                $refreshToken = trim((string) ($config['refreshToken'] ?? ''));

                if ($clientId === '' || $clientSecret === '' || $refreshToken === '') {
                    throw new \InvalidArgumentException(
                        'Google Drive disk: GOOGLE_DRIVE_CLIENT_ID, GOOGLE_DRIVE_CLIENT_SECRET, and GOOGLE_DRIVE_REFRESH_TOKEN must be set in .env (then run php artisan config:clear).'
                    );
                }

                $client = new \Google\Client();
                $client->setClientId($clientId);
                $client->setClientSecret($clientSecret);
                $client->setApplicationName((string) config('app.name', 'Laravel'));

                $tokenResponse = $client->fetchAccessTokenWithRefreshToken($refreshToken);

                if (isset($tokenResponse['error'])) {
                    $detail = $tokenResponse['error_description'] ?? $tokenResponse['error'];
                    throw new \RuntimeException(
                        'Google Drive OAuth refresh failed (401 / invalid_grant usually means the refresh token was revoked or the client secret changed). Generate a new refresh token and update GOOGLE_DRIVE_REFRESH_TOKEN. Detail: ' . $detail
                    );
                }

                if (empty($tokenResponse['access_token'])) {
                    throw new \RuntimeException(
                        'Google Drive OAuth did not return an access_token. Verify credentials and that the Google Cloud project has the Drive API enabled.'
                    );
                }

                $service = new \Google\Service\Drive($client);
                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folderId'] ?? '/', $options);
                $driver = new \League\Flysystem\Filesystem($adapter);

                return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            });
        } catch (\Exception $e) {
            // Log or handle error if necessary
        }
    }
}