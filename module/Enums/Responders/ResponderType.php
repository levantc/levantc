<?php

namespace Eta\Core\Module\Enums\Responders;

/**
 * Enum ResponderType
 *
 * Defines the types of responders that can be used to generate
 * HTTP responses from a UseCase result (BaseResponse).
 *
 * This enum allows controllers and factories to explicitly choose
 * the response type instead of relying on request headers or content negotiation.
 *
 * @package Levant\Enums
 */
enum ResponderType: string
{
    /**
     * JSON Responder
     *
     * Typically used for API endpoints or AJAX requests.
     */
    case JSON = 'json';

    /**
     * Inertia Responder
     *
     * Used when returning an Inertia page/component.
     */
    case INERTIA = 'inertia';

    /**
     * Redirect Back Responder
     * * Used for standard web requests (POST/PUT/DELETE) that should
     * redirect the user back to the previous page (e.g., after a failed validation or a simple update).
     */
    case REDIRECT_BACK = 'redirect_back';

    /**
     * Redirect To Route Responder
     * * Used for web requests that require redirecting to a specific
     * named route (e.g., redirecting to the index page after successfully deleting a resource).
     */
    case REDIRECT_TO_ROUTE = 'redirect_to_route';
}
