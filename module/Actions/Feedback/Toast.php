<?php

namespace Sadeem\Core\Module\Actions\Feedback;

use Sadeem\Core\Module\Actions\Action;
use Sadeem\Core\Module\Enums\Actions\Feedback\Variant;
use Sadeem\Core\Module\Events\ShowToast;

/**
 * Class Toast
 *
 * A value object that represents a user-facing toast notification.
 *
 * Responsibilities:
 * - Describe user feedback (success, error, warning, info)
 * - Carry a message intended for display
 * - Optionally include a list of Actions that the frontend can execute
 *
 * Important:
 * - This class is framework-agnostic (no HTTP, no JS, no UI logic)
 * - It does NOT execute actions; it only describes intent
 * - It is typically created by Result Enums and consumed by Responses
 */
final readonly class Toast
{
    /**
     * Toast constructor.
     *
     * @param Variant $variant
     *   The visual/semantic type of the toast.
     *   Expected values: success | error | warning | info
     *
     * @param string $title
     *    Human-readable title to be displayed to the user.
     *
     * @param string $message
     *   Human-readable message to be displayed to the user.
     *
     * @param Action[] $actions
     *   A list of actions that the frontend may offer to the user
     *   (e.g. redirect, trigger event, callback).
     */
    public function __construct(
        public Variant $variant,
        public string $title,
        public string $message,
        public array  $actions = []
    ) {}

    /**
     * Make a toast.
     *
     * @param Variant $variant
     * @param string $title
     * @param string $message*
     * @param Action[] $actions
     *
     * @return self
     */
    public static function make(Variant $variant, string $title, string $message, array $actions = []): self
    {
        return new self($variant, $title, $message, $actions);
    }

    /**
     * Dispatch the toast immediately via Laravel Broadcasting.
     *
     * This method sends the toast payload to the frontend in real-time
     * using a broadcast event (e.g., ShowToast).
     *
     * Use this when:
     * - You need instant UI feedback
     * - The user is already connected via Echo/WebSocket
     * - No page reload is involved
     *
     * Transport Layer:
     * - Laravel Broadcasting (WebSockets / Pusher / Reverb)
     */
    public function dispatch(): void
    {
        ShowToast::dispatch($this->toArray());
    }

    /**
     * Flash the toast into the session for the next HTTP request.
     *
     * This method stores the toast payload in the session using
     * Laravel's flash data mechanism. The toast will be available
     * on the next page load via Inertia shared props.
     *
     * Use this when:
     * - You are redirecting after an action
     * - You need the toast to appear after a full page navigation
     * - Broadcasting is not required
     *
     * Lifetime:
     * - Available for one request only
     */
    public function flash(): void
    {
        session()->flash('toast', $this->toArray());
    }

    /**
     * Convert the Toast into an array representation.
     *
     * This method is typically used by:
     * - Responses (to serialize data)
     * - Responders (to pass data to the frontend)
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'variant' => $this->variant->value,
            'title' => $this->title,
            'message' => $this->message,
            'actions' => array_map(
                fn (Action $action) => $action->toArray(),
                $this->actions
            ),
        ];
    }
}
