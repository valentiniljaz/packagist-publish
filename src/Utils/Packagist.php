<?php

namespace VI\PackagistPublish\Utils;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Packagist
{
    private string $baseApi = 'packagist.com';

    private function genAuthHeader(string $method, string $urlPath, string $apiKey, string $apiSecret, string $content = null): string {
        $cnonce = uniqid();
        $time = time();

        $params = [
            'cnonce' => $cnonce,
            'key' => $apiKey,
            'timestamp' => $time
        ];
        if ($content) {
            $params['body'] = $content;
        }
        uksort($params, 'strcmp');

        $stringToSign =
            strtoupper($method) . "\n"
            . $this->baseApi . "\n"
            . $urlPath . "\n"
            . http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $signature = base64_encode(
            hash_hmac('sha256', $stringToSign, $apiSecret, true)
        );

        return 'PACKAGIST-HMAC-SHA256 Key=' . $apiKey .
            ', Timestamp=' . $time .
            ', Cnonce=' . $cnonce .
            ', Signature=' . $signature;
    }

    /**
     * @param string $packageName
     * @param string $archiveFile
     * @param string $apiKey
     * @param string $apiSecret
     * @return int
     * @throws GuzzleException
     * @throws Exception
     */
    public function publish(string $packageName, string $archiveFile, string $apiKey, string $apiSecret): int {
        $client = new Client();

        $method = 'POST';
        $urlPath = '/api/packages/' . $packageName . '/artifacts/';
        $body = file_get_contents($archiveFile);
        $res = $client->request($method, $this->baseApi . $urlPath, [
            'headers' => [
                'Authorization' => $this->genAuthHeader($method, $urlPath, $apiKey, $apiSecret, $body),
                'Content-Type' => mime_content_type($archiveFile),
                'X-Filename' => basename($archiveFile),
            ],
            'body' => $body
        ]);

        if ($res->getStatusCode() === 201) {
            $body = json_decode($res->getBody(), true);
            return $body['id'];
        } else {
            throw new Exception('Response ' . $res->getStatusCode() . ' received: ' . "\n" . $res->getBody());
        }
    }
}