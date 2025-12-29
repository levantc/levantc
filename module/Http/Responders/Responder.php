<?php

namespace Sadeem\Core\Module\Http\Responders;

use Sadeem\Core\Module\Http\Responders\Options\ResponderOptions;

/**
 * Class Responder
 *
 * Base abstract responder that provides helper methods for formatting
 * and processing responses. All child responders must implement the `respond` method.
 */
abstract class Responder
{
    /**
     * Transform a BaseResponse + Options DTO into an HTTP response.
     *
     * Each child responder must implement this method to handle its specific Options DTO.
     *
     * @param ResponderOptions $options
     *   Strongly-typed options object. This ensures type safety for all responders.
     *
     * @return mixed
     *   The final HTTP response (JsonResponse, RedirectResponse, Inertia Response, etc.).
     */
    abstract public function respond(ResponderOptions $options): mixed;

    /**
     * Helper to format the response data (common logic for all responders)
     *
     * @param mixed $response
     * @return array
     */
    protected function formatResponse(mixed $response): array
    {
        return [
            'success' => $response->isSuccess(),
            'message' => $response->message(),
            'data' => $response->model ?? null,
        ];
    }

    /**
     * Helper to merge extra metadata if needed
     *
     * @param array $responseData
     * @param array $extra
     * @return array
     */
    protected function mergeMeta(array $responseData, array $extra = []): array
    {
        return array_merge($responseData, $extra);
    }
}
