<?php

namespace Sadeem\Core\Module\Http\Responders\Options;

use Sadeem\Core\Module\Responses\Response;

/**
 * Class InertiaResponderOptions
 *
 * Data Transfer Object (DTO) for the InertiaResponder.
 *
 * Extends BaseResponderOptions to include the specific Inertia component
 * to render along with optional extra metadata.
 */
class InertiaResponderOptions extends ResponderOptions
{
    /**
     * The Inertia component to render.
     *
     * Defaults to 'Dashboard/Index' if not specified.
     *
     * @var string
     */
    public readonly string $component;

    /**
     * Create a new InertiaResponderOptions instance.
     *
     * @param Response $response The mandatory BaseResponse object from the UseCase.
     * @param string $component The Inertia component to render (default: 'Dashboard/Index').
     * @param array<string, mixed> $extra Optional extra metadata (default: empty array).
     */
    public function __construct(
        Response $response,
        string $component = 'Dashboard/Index',
        array $extra = []
    ) {
        parent::__construct($response, $extra);
        $this->component = $component;
    }
}
