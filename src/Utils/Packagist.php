<?php

namespace VI\PackagistPublish\Utils;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Packagist
{
    private string $baseApi = 'packagist.com';

    private function genSignature(string $method, string $urlPath, string $apiKey, string $apiSecret): string {
        $params = [
            'cnonce' => uniqid(),
            'key' => $apiKey,
            'timestamp' => time()
        ];
        $stringToSign =
            strtoupper($method) . "\n"
            . $this->baseApi . "\n"
            . $urlPath . "\n"
            . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        return base64_encode(
            hash_hmac('sha256', $stringToSign, $apiSecret, true)
        );
    }

    /**
     * @param string $packageName
     * @param string $archiveFile
     * @param string $apiKey
     * @param string $apiSecret
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    public function publish(string $packageName, string $archiveFile, string $apiKey, string $apiSecret) {
        $client = new Client();

        $method = 'POST';
        $urlPath = '/api/packages/' . $packageName . '/artifacts/';
        $res = $client->request($method, $this->baseApi . $urlPath, [
            'headers' => [
                'Authorization' => $this->genSignature($method, $urlPath, $apiKey, $apiSecret),
                'Content-Type' => mime_content_type($archiveFile),
                'X-Filename' => basename($archiveFile),
            ],
            'body' => file_get_contents($archiveFile)
        ]);

        if ($res->getStatusCode() === 201) {
            $body = $res->getBody();
            return json_decode($body);
        } else {
            throw new Exception('Response ' . $res->getStatusCode() . ' received: ' . "\n" . $res->getBody());
        }
    }
}