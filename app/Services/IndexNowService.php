<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.indexnow.org/indexnow';
    
    public function __construct()
    {
        $this->apiKey = config('seo.indexnow.key');
    }
    
    /**
     * Submit single URL to IndexNow
     */
    public function submitUrl($url)
    {
        return $this->submitUrls([$url]);
    }
    
    /**
     * Submit multiple URLs to IndexNow
     */
    public function submitUrls(array $urls)
    {
        try {
            $response = Http::post($this->apiUrl, [
                'host' => parse_url(config('app.url'), PHP_URL_HOST),
                'key' => $this->apiKey,
                'keyLocation' => config('app.url') . '/indexnow',
                'urlList' => $urls,
            ]);
            
            if ($response->successful()) {
                Log::info('URLs submitted to IndexNow successfully', ['urls' => $urls]);
                return true;
            }
            
            Log::error('IndexNow submission failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('IndexNow exception: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Auto-submit when new ad is created
     */
    public function submitNewAd($adUrl)
    {
        return $this->submitUrl($adUrl);
    }
}