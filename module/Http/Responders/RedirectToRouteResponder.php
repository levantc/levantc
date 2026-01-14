<?php

namespace Sadeem\Core\Module\Http\Responders;

use Sadeem\Core\Module\Http\Responders\Options\RedirectToRouteResponderOptions;
use Sadeem\Core\Module\Http\Responders\Options\ResponderOptions;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

/**
 * Class RedirectToRouteResponder
 *
 * Generates a redirect to a specific named route with parameters and optional
 * flash messages based on the success/failure of the UseCase response.
 */
class RedirectToRouteResponder extends Responder
{
    /**
     * Transform the given options into a redirect to route response.
     *
     * @param ResponderOptions $options
     * Must be an instance of RedirectToRouteResponderOptions.
     *
     * @return RedirectResponse
     */
    public function respond(ResponderOptions $options): RedirectResponse
    {
        if (! $options instanceof RedirectToRouteResponderOptions) {
            throw new InvalidArgumentException(
                'Expected instance of RedirectToRouteResponderOptions'
            );
        }

        // Extract the Response and route details from options
        $response = $options->response;

        // Add feedback message to flash data
        $flash = $options->flash;
        $flash['message'] = $response->message();

        // Return the redirect to route response
        return redirect()
            ->route($options->routeName, $options->parameters)
            ->with($flash);
    }
}
