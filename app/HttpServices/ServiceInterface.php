<?php

namespace App\HttpServices;
use Illuminate\Http\Client\Response;

interface ServiceInterface
{
    /**
     * Send a GET request.
     *
     * @param string $path
     * @param array $query
     * @return Response
     */
    public function get(string $path, array $query = []): Response;

    /**
     * Send a POST request.
     *
     * @param string $path
     * @param array $data
     * @return Response
     */
    public function post(string $path, array $data = []): Response;

    /**
     * Send a PUT request.
     *
     * @param string $path
     * @param array $data
     * @return Response
     */
    public function put(string $path, array $data = []): Response;

    /**
     * Send a DELETE request.
     *
     * @param string $path
     * @return Response
     */
    public function delete(string $path): Response;
}
