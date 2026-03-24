<?php

namespace Eta\Core\Module\Http\Responders\Options;

use Eta\Core\Module\Responses\Response;

/**
 * Class RedirectToRouteResponderOptions
 *
 * Options DTO for RedirectToRouteResponder.
 *
 * Encapsulates the target route name, parameters, and flash messages
 * needed to generate a redirect to a specific named route.
 */
class RedirectToRouteResponderOptions extends ResponderOptions
{
    /**
     * Additional flash data to include in the redirect session.
     *
     * @var array<string, mixed>
     */
    public array $flash = [];

    /**
     * RedirectToRouteResponderOptions constructor.
     *
     * @param Response $response
     * The BaseResponse returned by the UseCase.
     * @param string $routeName
     * The name of the route to redirect to.
     * @param array<string, mixed> $parameters
     * Parameters to pass to the route() helper.
     * @param array<string, mixed> $extra
     * Extra metadata to include in the response (optional).
     * @param array<string, mixed> $flash
     * Flash data for redirect session (optional).
     */
    public function __construct(
        Response $response,
        public string $routeName,
        public array $parameters = [],
        array $extra = [],
        array $flash = []
    ) {
        parent::__construct($response, $extra);
        $this->flash = $flash;
    }
}
