<?php

namespace Sadeem\Core\Module\Enums\Responders;

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
     * Redirect Responder
     *
     * Used for standard web requests (POST/PUT/DELETE) that redirect back.
     */
    case REDIRECT = 'redirect';
}
