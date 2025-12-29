<?php

namespace Sadeem\Core\Module\Http\Responders\Options;

use Sadeem\Core\Module\Responses\Response;

/**
 * Class ResponderOptions
 *
 * Base Data Transfer Object (DTO) for all responders.
 *
 * This class guarantees:
 * 1. A mandatory Response object from the UseCase.
 * 2. Optional extra metadata to pass along to the responder.
 *
 * All responders should extend this DTO to maintain type safety and reduce boilerplate.
 */
class ResponderOptions
{
    /**
     * The response object returned from the UseCase.
     *
     * Contains:
     *  - success status via isSuccess()
     *  - message string
     *  - model/data payload (e.g., a User model)
     *
     * @var Response
     */
    public readonly Response $response;

    /**
     * Optional extra metadata to pass to the responder.
     *
     * Can be used for:
     *  - flags
     *  - custom messages
     *  - additional links or data
     *
     * @var array<string, mixed>
     */
    public readonly array $extra;

    /**
     * Create a new BaseResponderOptions instance.
     *
     * @param Response $response The mandatory BaseResponse object from the UseCase.
     * @param array<string, mixed> $extra Optional extra metadata (default: empty array).
     */
    public function __construct(Response $response, array $extra = [])
    {
        $this->response = $response;
        $this->extra = $extra;
    }
}
