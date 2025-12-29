<?php

namespace Sadeem\Core\Module\Http\Responders;

use Sadeem\Core\Module\Http\Responders\Options\RedirectBackResponderOptions;
use Sadeem\Core\Module\Http\Responders\Options\ResponderOptions;
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
        $response = $options->response;
        $flash = $options->flash;
        $flash['message'] = $response->message();
        return redirect()->back()->with($flash);
    }
}
