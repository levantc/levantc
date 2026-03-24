<?php

namespace Eta\Core\Module\Actions;

use Eta\Core\Module\Enums\Actions\Type;
use InvalidArgumentException;

/**
 * Class Action
 *
 * Represents an actionable instruction returned by a Result.
 * This can be a redirect, frontend event, or backend callback.
 *
 * Responsibilities:
 * - Encapsulate an action type and its payload
 * - Validate payload automatically based on the type
 * - Provide named constructors for type-safe creation
 *
 * Example usage:
 *   Action::redirect(route('home'), 'Go Home');
 *   Action::event('fetched');
 *   Action::callback(fn() => doSomething());
 */
class Action
{
    /**
     * Constructor
     *
     * @param Type $type The type of the action (REDIRECT | EVENT | CALLBACK)
     * @param array $payload Extra data required to execute the action
     *
     * @throws InvalidArgumentException if the payload is missing required keys
     */
    public function __construct(
        public Type $type,
        public array $payload = []
    ) {
        $this->validatePayload(); // Immediately validate payload based on type
    }

    /**
     * Validate the payload according to the action type.
     * Enforces developers to provide the correct fields.
     *
     * @throws InvalidArgumentException
     */
    private function validatePayload(): void
    {
        match($this->type) {
            Type::REDIRECT => $this->validateRedirect(),
            Type::EVENT => $this->validateEvent(),
            Type::CALLBACK => $this->validateCallback(),
            default => null,
        };
    }

    /**
     * REDIRECT action must have 'url' and 'label' keys.
     */
    private function validateRedirect(): void
    {
        if (!isset($this->payload['url']) || !is_string($this->payload['url'])) {
            throw new InvalidArgumentException("REDIRECT action requires a 'url' string in payload");
        }
        if (!isset($this->payload['label']) || !is_string($this->payload['label'])) {
            throw new InvalidArgumentException("REDIRECT action requires a 'label' string in payload");
        }
    }

    /**
     * EVENT action must have an 'event' key as a string.
     */
    private function validateEvent(): void
    {
        if (!isset($this->payload['event']) || !is_string($this->payload['event'])) {
            throw new InvalidArgumentException("EVENT action requires an 'event' string in payload");
        }
    }

    /**
     * CALLBACK action must have a 'callback' key as a callable.
     */
    private function validateCallback(): void
    {
        if (!isset($this->payload['callback']) || !is_callable($this->payload['callback'])) {
            throw new InvalidArgumentException("CALLBACK action requires a 'callback' callable in payload");
        }
    }

    /**
     * Convert Action to array for frontend consumption.
     *
     * @return array{
     *   type: string,
     *   payload: array
     * }
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type->value, // Convert Enum to string
            'payload' => $this->payload,  // Pass ready-to-use data
        ];
    }

    /**
     * Named constructor for REDIRECT action.
     *
     * @param string $url Target URL
     * @param string $label Button label
     * @return self
     */
    public static function redirect(string $url, string $label): self
    {
        return new self(Type::REDIRECT, ['url' => $url, 'label' => $label]);
    }

    /**
     * Named constructor for EVENT action.
     *
     * @param string $event Event name to trigger on the frontend
     * @param array $params Optional extra parameters
     * @return self
     */
    public static function event(string $event, array $params = []): self
    {
        return new self(Type::EVENT, ['event' => $event, 'params' => $params]);
    }

    /**
     * Named constructor for CALLBACK action.
     *
     * @param callable $callback PHP callable to execute on the backend
     * @return self
     */
    public static function callback(callable $callback): self
    {
        return new self(Type::CALLBACK, ['callback' => $callback]);
    }
}
