<?php

namespace Sadeem\Core\Module\Http\Responders;

use Illuminate\Http\JsonResponse;

/**
 * Class JsonResponder
 *
 * Handles API requests and returns standardized JSON responses.
 */
class JsonResponder extends Responder
{
    /**
     * Return a JSON response.
     *
     * @param mixed $response The response object
     * @param mixed ...$options
     *      $options[0] => int $status HTTP status code override
     *      $options[1] => array $extra Additional metadata
     */
    public function respond($response, ...$options): JsonResponse
    {
        $status = $options[0] ?? $response->statusCode();
        $extra = $options[1] ?? [];

        $data = $this->mergeMeta($this->formatResponse($response), $extra);

        return response()->json($data, $status);
    }
}
