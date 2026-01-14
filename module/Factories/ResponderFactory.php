<?php

namespace Sadeem\Core\Module\Factories;

use Sadeem\Core\Module\Enums\Responders\ResponderType;
use Sadeem\Core\Module\Http\Responders\InertiaResponder;
use Sadeem\Core\Module\Http\Responders\JsonResponder;
use Sadeem\Core\Module\Http\Responders\RedirectBackResponder;
use Sadeem\Core\Module\Http\Responders\RedirectToRouteResponder;
use Sadeem\Core\Module\Http\Responders\Responder;

/**
 * Class ResponderFactory
 *
 * Factory responsible for creating Responder instances based on a given type.
 *
 * This approach allows explicit control over which responder to use, instead of
 * relying on Request headers or content negotiation.
 */
class ResponderFactory
{
    /**
     * Create a Responder instance based on the provided ResponderType.
     *
     * @param ResponderType $type
     *   The type of responder to create (JSON, INERTIA, REDIRECT).
     *
     * @return Responder
     *   An instance of the requested Responder.
     */
    public function make(ResponderType $type): Responder
    {
        return match ($type) {
            ResponderType::JSON => new JsonResponder(),
            ResponderType::INERTIA => new InertiaResponder(),
            ResponderType::REDIRECT_BACK => new RedirectBackResponder(),
            ResponderType::REDIRECT_TO_ROUTE => new RedirectToRouteResponder(),
        };
    }
}
