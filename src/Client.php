<?php

namespace RickWest\WordPress;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    private string $baseUrl;
    private array $parameters;
    private array $attachments = [];

    public function __construct(string $baseUrl = null, array $parameters = [])
    {
        $this->baseUrl = $baseUrl ?? strval(config('wordpress-api.url'));
        $this->parameters = $parameters;
    }

    /**
     * @return PendingRequest
     */
    private function prepareRequest(): PendingRequest
    {
        $http = Http::acceptJson();

        if (isset($this->parameters['username']) && isset($this->parameters['password'])) {
            $http = $http->withBasicAuth($this->parameters['username'], $this->parameters['password']);
        }

        foreach ($this->attachments as $attachment) {
            [$name, $contents, $filename, $headers] = $attachment;
            $http = $http->attach($name, $contents, $filename, $headers);
        }

        return $http;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return Response
     */
    public function send(string $method, string $endpoint, array $options = []): Response
    {
        return $this->prepareRequest()->send($method, $this->baseUrl.$endpoint, $options);
    }

    /**
     * @param string $endpoint
     * @param array $options
     * @return Response
     */
    public function post(string $endpoint, array $options = []): Response
    {
        return $this->prepareRequest()->post($this->baseUrl.$endpoint, $options);
    }

    /**
     * Attach a file to the request.
     *
     * @param  string|array  $name
     * @param  string|resource  $contents
     * @param  string|null  $filename
     * @param  array  $headers
     * @return $this
     */
    public function attach(string $name, $contents, ?string $filename = null, array $headers = []): self
    {
        $this->attachments[] = [$name, $contents, $filename, $headers];
        return $this;
    }
}
