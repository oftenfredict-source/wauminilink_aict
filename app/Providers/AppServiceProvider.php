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

        // Skip subdirectory detection for local development
        // Only apply subdirectory logic for production/staging environments
        $appEnv = env('APP_ENV', 'local');
        $skipAutoDetection = env('APP_SKIP_SUBDIRECTORY_AUTO_DETECT', false);

        // Handle subdirectory hosting (e.g., /demo/)
        // This ensures asset() helper includes the subdirectory in URLs
        $subdirectory = env('APP_SUBDIRECTORY', '');

        // Auto-detect subdirectory from request if not set in env
        // Skip auto-detection if:
        // 1. Already set in env
        // 2. Local environment (unless explicitly enabled)
        // 3. Skip flag is set
        // 4. APP_URL already contains a path (not just domain)
        if (empty($subdirectory) && !$skipAutoDetection && $appEnv !== 'local' && request()) {
            $appUrl = config('app.url');
            // If APP_URL already contains a path (not just domain), don't auto-detect
            $urlPath = parse_url($appUrl, PHP_URL_PATH);
            if (empty($urlPath) || $urlPath === '/') {
                // Try to detect from SCRIPT_NAME first (more reliable for subdirectory hosting)
                if (isset($_SERVER['SCRIPT_NAME'])) {
                    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
                    if ($scriptPath !== '/' && $scriptPath !== '\\' && $scriptPath !== '.') {
                        $subdirectory = rtrim($scriptPath, '/');
                    }
                }

                // Fallback: try to detect from request URI
                if (empty($subdirectory)) {
                    $path = parse_url(request()->getRequestUri(), PHP_URL_PATH);
                    // Extract subdirectory from path (e.g., /demo/... -> /demo)
                    if (preg_match('#^/([^/]+)/#', $path, $matches)) {
                        // Check if it's not a route (common Laravel routes)
                        $commonRoutes = ['login', 'register', 'api', 'storage', 'assets', 'css', 'js', 'images'];
                        if (!in_array($matches[1], $commonRoutes)) {
                            $subdirectory = '/' . $matches[1];
                        }
                    }
                }
            }
        }

        // Set asset URL to include subdirectory
        if (!empty($subdirectory)) {
            $appUrl = config('app.url');
            // Ensure subdirectory doesn't already exist in APP_URL
            if (strpos($appUrl, $subdirectory) === false) {
                $appUrl = rtrim($appUrl, '/') . $subdirectory;
            }
            URL::forceRootUrl($appUrl);

            // Also update the public disk URL to include subdirectory
            config(['filesystems.disks.public.url' => $appUrl . '/storage']);
        } else {
            // Let Laravel auto-detect the URL based on the request host.
            // Forcing URL via APP_URL can cause routing / 404 issues on servers if .env is misconfigured.
        }

        // Force HTTPS on live servers to prevent Mixed Content errors for CSS/JS
        $host = request()->getHost();
        $isLocalHost = in_array($host, ['localhost', '127.0.0.1', '::1']) || str_ends_with($host, '.test');

        if (!$isLocalHost) {
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

        // Register Google Drive driver
        try {
            Storage::extend('google', function ($app, $config) {
                $options = [];

                if (!empty($config['teamDriveId'] ?? null)) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }

                if (!empty($config['sharedDriveId'] ?? null)) {
                    $options['sharedDriveId'] = $config['sharedDriveId'];
                }

                $client = new \Google\Client();
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);

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