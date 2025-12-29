<?php

namespace Sadeem\Core\Module\Http\Responders\Options;

use Sadeem\Core\Module\Responses\Response;

/**
 * Class RedirectBackResponderOptions
 *
 * Options DTO for RedirectBackResponder.
 *
 * Encapsulates all the data needed by the RedirectBackResponder to generate
 * a proper redirect response with flash messages or additional metadata.
 */
class RedirectBackResponderOptions extends ResponderOptions
{
    /**
     * Additional flash data to include in the redirect session.
     *
     * @var array<string, mixed>
     */
    public array $flash = [];

    /**
     * RedirectBackResponderOptions constructor.
     *
     * @param Response $response
     *   The BaseResponse returned by the UseCase.
     * @param array<string, mixed> $extra
     *   Extra metadata to include in the response (optional).
     * @param array<string, mixed> $flash
     *   Flash data for redirect session (optional).
     */
    public function __construct(Response $response, array $extra = [], array $flash = [])
    {
        parent::__construct($response, $extra);
        $this->flash = $flash;
    }
}
