<?php

namespace Sadeem\Core\Module\Http\Responders;

use Sadeem\Core\Module\Http\Responders\Options\InertiaResponderOptions;
use Sadeem\Core\Module\Http\Responders\Options\ResponderOptions;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

/**
 * Class InertiaResponder
 *
 * Converts a BaseResponse + InertiaResponderOptions into an Inertia HTTP response.
 * Ensures type safety by requiring InertiaResponderOptions.
 */
class InertiaResponder extends Responder
{
    /**
     * Transform the given options into an Inertia HTTP response.
     *
     * @param ResponderOptions $options
     *   Must be an instance of InertiaResponderOptions. Contains:
     *     - response: The BaseResponse object from the UseCase.
     *     - component: The Inertia component to render.
     *     - extra: Optional metadata to merge with the response data.
     *
     * @return InertiaResponse
     */

    public function respond(ResponderOptions $options): InertiaResponse
    {
        // Ensure correct type for type safety
        if (! $options instanceof InertiaResponderOptions) {
            throw new \InvalidArgumentException(
                'Expected instance of InertiaResponderOptions'
            );
        }

        // Extract the Response from options
        $response = $options->response;

        // Format the response data
        $responseData = $this->formatResponse($response);

        // Merge extra metadata if provided
        $data = $this->mergeMeta($responseData, $options->extra);

        // Return the Inertia response with the specified component
        return Inertia::render($options->component, $data);
    }
}
