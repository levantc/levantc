<?php

namespace Eta\Core\Module\Http\Responders;

use Eta\Core\Module\Http\Responders\Options\RedirectBackResponderOptions;
use Eta\Core\Module\Http\Responders\Options\ResponderOptions;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

/**
 * Class RedirectBackResponder
 *
 * Generates a redirect back response with optional flash messages
 * based on the success/failure of the UseCase response.
 */
class RedirectBackResponder extends Responder
{
    /**
     * Transform the given options into a redirect back response.
     *
     * @param ResponderOptions $options
     *   Must be an instance of RedirectBackResponderOptions.
     *
     * @return RedirectResponse
     */
    public function respond(ResponderOptions $options): RedirectResponse
    {
        if (! $options instanceof RedirectBackResponderOptions) {
            throw new InvalidArgumentException(
                'Expected instance of RedirectBackResponderOptions'
            );
        }

        // Extract the Response from options
        $response = $options->response;

        // Flash the toast to the session for the next request
        $response->toast()->flash();

        // If the use case response indicates failure, redirect back with a form-level error message
        if (!$response->isSuccess()){
            return back()->withErrors([
                'form' => $response->message()
            ])->withInput();
        }

        // If the operation succeeds, simply redirect back without errors
        return back();
    }
}
