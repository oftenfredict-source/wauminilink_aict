<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Illuminate\Support\Facades\Log;
use App\Models\SystemSetting;

class GoogleAuthController extends Controller
{
    /**
     * Start the Google OAuth process
     */
    public function auth()
    {
        // Check permission
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $client = $this->getGoogleClient();
        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    /**
     * Handle the callback from Google
     */
    public function callback(Request $request)
    {
        // Check permission
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$request->has('code')) {
            return redirect()->route('settings.index', ['category' => 'backup'])
                ->with('error', 'Google authentication failed: No code provided.');
        }

        try {
            $client = $this->getGoogleClient();
            $token = $client->fetchAccessTokenWithAuthCode($request->code);

            if (isset($token['error'])) {
                throw new \Exception('Error fetching access token: ' . $token['error_description']);
            }

            if (!isset($token['refresh_token'])) {
                // If refresh_token is missing, it might mean the user already authorized the app
                // and we need to force the prompt or check if we already have it.
                // However, for this specific fix, we want the refresh token.
                return redirect()->route('settings.index', ['category' => 'backup'])
                    ->with('warning', 'Connection successful, but no refresh token was provided. If you need to update the refresh token, please disconnect the app from your Google Account security settings first, or try again.');
            }

            $refreshToken = $token['refresh_token'];

            // Save to .env
            $this->writeEnv([
                'GOOGLE_DRIVE_REFRESH_TOKEN' => $refreshToken
            ]);

            // Also update SystemSetting if it exists
            SystemSetting::setValue('google_drive_refresh_token', $refreshToken, 'string');

            return redirect()->route('settings.index', ['category' => 'backup'])
                ->with('success', 'Google Drive connected successfully! Refresh token updated.');

        } catch (\Exception $e) {
            Log::error('Google Drive Auth Error: ' . $e->getMessage());
            return redirect()->route('settings.index', ['category' => 'backup'])
                ->with('error', 'Google Drive authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Get configured Google Client
     */
    private function getGoogleClient()
    {
        $client = new GoogleClient();
        
        // Get credentials from env or settings
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');

        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri(url('/super-admin/google/callback'));
        $client->addScope(GoogleDrive::DRIVE_FILE);
        $client->addScope(GoogleDrive::DRIVE_METADATA_READONLY);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return $client;
    }

    /**
     * Helper to write to .env file
     */
    private function writeEnv(array $data): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }
        
        $content = file_get_contents($envPath);
        
        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $line = $key . '="' . preg_replace('/\n|\r/', '', $value) . '"';
            
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $line, $content);
            } else {
                $content .= "\n" . $line;
            }
        }
        
        file_put_contents($envPath, $content);
        
        // Clear config cache to ensure new env values are loaded
        try {
            \Illuminate\Support\Facades\Artisan::call('config:clear');
        } catch (\Exception $e) {
            Log::warning('Could not clear config cache after updating .env: ' . $e->getMessage());
        }
    }
}
