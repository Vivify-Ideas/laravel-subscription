<?php

namespace Userdesk\Subscription\Services\Common;

use Http\Client\HttpClient;
use Http\Client\Curl\Client as CurlClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\StreamFactory\GuzzleStreamFactory;

use GuzzleHttp\Psr7\Request;

use Psr\Http\Message\ResponseInterface;

class RestClient {

    public $httpClient;

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl = '')
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Execute GET request
     *
     * @param  string $endpointUri
     * @param  array $queryString
     * @return [type]
     */
    public function get($endpointUri, $queryString = [])
    {
        return $this->send('GET', $endpointUri . '?' . http_build_query($queryString));
    }

    /**
     * Execute POST request
     *
     * @param  string $endpointUri
     * @param  array  $data
     * @return [type]
     */
    public function post($endpointUri, $data = [])
    {
        return $this->send('POST', $endpointUri, json_encode($data, true));
    }

    /**
     * Execute PUT request
     *
     * @param  string $endpointUri
     * @param  array  $putData
     * @return [type]
     */
    public function put($endpointUri, $putData = [])
    {
        return $this->send('PUT', $endpointUri, json_encode($data, true));
    }

    /**
     * Execute DELETE request
     *
     * @param  string $endpointUri
     * @return [type]
     */
    public function delete($endpointUri)
    {
        return $this->send('DELETE', $endpointUri);
    }

    /**
     * Execute HTTP request
     *
     * @param  string $method
     * @param  string $endpointUri
     * @param  string $body
     * @param  array $headers
     * @return [type]
     */
    protected function send($method, $endpointUri, $body = null, array $headers = [])
    {
        $headers = array_merge($headers, self::getDefaultHeaders());
        $endpointUrl = $this->baseUrl . $endpointUri;

        $request = new Request($method, $endpointUrl, $headers, $body);
        $response = $this->getHttpClient()->sendRequest($request);

        return $this->handleResponse($response);
    }

    /**
     * Handle HTTP response
     *
     * @param  ResponseInterface $response
     * @return [type]
     */
    protected function handleResponse(ResponseInterface $response)
    {
        $status = $response->getStatusCode();

        $data = (string) $response->getBody();
        $jsonResponseData = json_decode($data, false);
        $body = $data && $jsonResponseData === null ? $data : $jsonResponseData;

        return ['status_code' => $status, 'body' => $body];
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $options = [
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false
            ];

            $this->httpClient = new CurlClient(new GuzzleMessageFactory(), new GuzzleStreamFactory(), $options);
        }

        return $this->httpClient;
    }

    /**
     * @return array
     */
    protected function getDefaultHeaders() {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];
    }
}