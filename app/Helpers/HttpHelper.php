<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait HttpHelper
{

    public function getApiUrl($url)
    {
        $base_url = config('endpoints.football.base_url');
        $clean = ltrim($url);
        $start_backslash = $clean[0];
        if ($start_backslash !== '/') {
            $url = '/' . $clean;
        }
        return $base_url . $url;
    }

    public static function getClientWithAuth()
    {
        $auth_type = config('endpoints.football.auth_type');
        $token = config('endpoints.football.token');

        if ($token != '' && $auth_type != '') {
            Log::info("Api with auth");
            return Http::withHeaders([(string)$auth_type => $token]);
        }

        throw new \Exception("Verified you auth type and token", 500);

    }

    /**
     * @throws \Exception
     */
    public function httpGet($url, $auth = false, $query = null)
    {

        $api_url = $this->getApiUrl($url);

        if ($auth) {
            $response = self::getClientWithAuth()->get($api_url, $query);
        } else {
            $response = Http::get($url, $query);
        }

        if ($response->successful()) {
            return $response;
        }
        if ($response->failed()) // errors
        {
            Log::error("Response failed");
            Log::error($response);

            $errorCode = 503;
            if (isset($response['errorCode'])) {
                $errorCode = $response['errorCode'];
            }

            if (isset($response['error'])) {
                $errorCode = $response['error'];
            }

            throw new \Exception($response['message'], $errorCode);
        }

        throw new \Exception("could not connect with " . config('endpoints.football.base_url'), 503);

    }

}
