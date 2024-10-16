<?php

namespace App\HttpServices;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Pool;

abstract class BaseService implements ServiceInterface
{
    /**
     * The base URL of the Service.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * Flag to determine if the service should be accessed directly.
     *
     * @var bool
     */
    protected bool $directAccess = false;

    /**
     * BaseService constructor.
     * Initializes the base URL.
     */
    public function __construct()
    {
        $this->baseUrl = $this->directAccess
            ? $this->getBaseUrl()
            : config('services.submission.base_uri');
    }

    /**
     * Make an HTTP request to the specified path.
     *
     * @param string $method
     * @param string $path
     * @param array $options
     * @return Response
     */
    protected function request(string $method, string $path, array $options = []): Response
    {
        $url = $this->baseUrl . '/' . $this->getServicePrefix() . '/' . ltrim($path, '/');
        return Http::withHeaders($this->getHeaders())->send($method, $url, $options);
    }

    /**
     * Make an asynchronous HTTP request to the specified path.
     *
     * @param string $method
     * @param string $path
     * @param array $options
     * @return array
     */
    protected function asyncRequest(string $method, string $path, array $options = []): array
    {
        $url = $this->baseUrl . '/' . $this->getServicePrefix() . '/' . ltrim($path, '/');

        // Create an HTTP pool to handle multiple requests
        $responses = Http::pool(function (Pool $pool) use ($options, $url, $method) {
            return collect($options)->map(function ($option) use ($pool, $url, $method) {
                return $pool->send($method, $url, ['json' => $option]);
            });
        });

        return $responses; // Return the array of responses
    }

    /**
     * Get the headers for the HTTP request.
     *
     * @param array $headers
     * @return array
     */
    public function getHeaders(array $headers = []): array
    {
        return array_merge([
            'Accept' => 'application/json',
        ], $headers);
    }

    /**
     * Get the service-specific URL prefix.
     *
     * @return string
     */
    abstract protected function getServicePrefix(): string;

    /**
     * Send a GET request.
     *
     * @param string $path
     * @param array $query
     * @return Response
     */
    public function get(string $path, array $query = []): Response
    {
        return $this->request('get', $path, ['query' => $query]);
    }

    /**
     * Send a POST request.
     *
     * @param string $path
     * @param array $data
     * @return Response
     */
    public function post(string $path, mixed $data = []): Response
    {
        return $this->request('post', $path, ['json' => $data]);
    }

    /**
     * Send a PUT request.
     *
     * @param string $path
     * @param array $data
     * @return Response
     */
    public function put(string $path, array $data = []): Response
    {
        return $this->request('put', $path, ['json' => $data]);
    }

    /**
     * Send a DELETE request.
     *
     * @param string $path
     * @return Response
     */
    public function delete(string $path): Response
    {
        return $this->request('delete', $path);
    }

    /**
     * Get the service-specific base URL.
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return '';
    }

    public function asyncPost(string $path, array $data = []): array
    {
        return $this->asyncRequest('post', $path, $data);
    }

    public function syncPost(string $path, array $options = []): array
    {
        return array_map(function ($option) use ($path) {
            return $this->post($path, $option);
        }, $options);
    }
}
