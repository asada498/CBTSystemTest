<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Googleapikey;
class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('google', function($app, $config) {
            $client = new \Google_Client();
            $client->setAccessType('offline');
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->setApprovalPrompt('force');

            //dd($config, $config['clientId']);
            $query = Googleapikey::select('*')->first();
            $accessToken = $query->value('access_token');
            $refreshToken = $query->value('refresh_token');
            $timeTokenIssuedInMiliseconds = strtotime($query->value('updated_at'));
            $expiredIn = $query->value('expired_in');
            $currentTimeInMiliseconds = strtotime("now");
            error_log($timeTokenIssuedInMiliseconds);
            error_log($expiredIn);
            error_log($currentTimeInMiliseconds);

            // token expired. get new token and save in database            
            if ($timeTokenIssuedInMiliseconds + $expiredIn < $currentTimeInMiliseconds)
            {
                error_log("refresh token needed");
                $client->refreshToken($refreshToken);
                $accessToken = $client->getAccessToken();
                //dd($config, $client->refreshToken($refreshToken),$accessToken, $refreshToken);
                Googleapikey::first()->update(['access_token' => $accessToken['access_token']]);                
            }

            $client->setAccessToken($accessToken);
            // $serviceGoogleDrive = new \Google_Service_Drive($client);
            // $serviceGoogleSheet = new \Google_Service_Sheets($client);
            // $serviceGoogleSheetRequest = new \Google_Service_Sheets_Request();
            // $serviceGoogleSheetBatchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest();

            // $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['folderId']);
            // return new \League\Flysystem\Filesystem($adapter);;
            // return [$serviceGoogleDrive,$serviceGoogleSheet,$serviceGoogleSheetRequest,$serviceGoogleSheetBatchUpdateRequest];
            return $client;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}