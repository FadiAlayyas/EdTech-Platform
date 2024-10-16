<?php

namespace App\HttpServices\Services;

use App\HttpServices\BaseService;
use Illuminate\Http\Client\Response;

class SubmissionHttpService extends BaseService
{
    private const ASYNC_THRESHOLD = 5;

    protected function getServicePrefix(): string
    {
        return '';
    }

    protected function getBaseUrl(): string
    {
        return config('services.submission.base_uri'); // e.g., https://jsonplaceholder.typicode.com
    }

    public function logSubmissions(array $submissions): array
    {
        $successfulResponses = [];
        $errorResponses = [];
        $path = 'posts';

        // Send asynchronously or synchronously
        if (count($submissions) >= self::ASYNC_THRESHOLD) {
            $responses = $this->asyncPost($path, $submissions);
        } else {
            $responses = $this->syncPost($path, $submissions);
        }

        // All responses Process
        foreach ($responses as $response) {
            $this->processResponse($response, $successfulResponses, $errorResponses);
        }

        return $successfulResponses;
    }

    private function processResponse(Response $response, array &$successfulResponses, array &$errorResponses): void
    {
        if ($response->successful()) {
            $successfulResponses[] = $response->json();
        } else {
            $errorResponses[] = [
                'status' => $response->status(),
                'data' => $response->json(),
            ];
        }
    }
}
