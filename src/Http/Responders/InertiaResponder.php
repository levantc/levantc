<?php

namespace Levantc\Http\Responders;

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Levantc\Http\Responders\Options\InertiaResponderOptions;
use Levantc\Http\Responders\Options\ResponderOptions;

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
     * @param  ResponderOptions  $options
     *                                     Must be an instance of InertiaResponderOptions. Contains:
     *                                     - response: The BaseResponse object from the UseCase.
     *                                     - component: The Inertia component to render.
     *                                     - extra: Optional metadata to merge with the response data.
     */
    public function respond(ResponderOptions $options): InertiaResponse
    {
        // Ensure the correct type for type safety
        if (! $options instanceof InertiaResponderOptions) {
            throw new \InvalidArgumentException(
                'Expected instance of InertiaResponderOptions'
            );
        }

        // Validate component existence before rendering
        $this->ensureComponentExists($options->component);

        // Extract the Response from options
        $response = $options->response;

        // Format the response data
        $responseData = $this->formatResponse($response);

        // Share the toast directly with Inertia for the current request
        // We only share if there's no existing toast flashed in the session to avoid overwriting it
        if ($toast = $response->toast()) {
            $existingFlash = Inertia::getShared('flash') ?? [];
            if (! isset($existingFlash['toast']) || ! $response->isSuccess()) {
                Inertia::share('flash', array_merge($existingFlash, [
                    'toast' => $toast->toArray(),
                ]));
            }
        }

        // Merge extra metadata if provided
        $data = $this->mergeMeta($responseData, $options->extra);

        // Return the Inertia response with the specified component
        return Inertia::render(
            component: $options->component,
            props: $data
        );
    }
}
