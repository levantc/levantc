<?php

namespace Sadeem\Core\Module\Responses;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Sadeem\Core\Module\Actions\Feedback\Toast;
use Sadeem\Core\Module\Contracts\Result\ResultContract;
use Sadeem\Core\Module\Enums\Actions\Feedback\Variant;

/**
 * Context-aware base Response for UseCase operations.
 *
 * This class introduces a lightweight "Context" mechanism that allows
 * passing runtime metadata alongside the Result and optional data.
 *
 * The Context is typically used for:
 * - Translation placeholders (e.g. ['name' => 'Muath'])
 * - Dynamic values injected into messages or titles
 * - Per-response overrides without mutating the Result itself
 *
 * How it works:
 * - defaultContext(): defines safe defaults per Response subclass
 * - $context (constructor): runtime overrides provided by the caller
 * - getContext(): merges defaults with overrides (overrides take precedence)
 */
readonly abstract class Response
{
    /**
     * Runtime context overrides such as translation placeholders.
     *
     * IMPORTANT:
     * - Do NOT use constructor property promotion with a default value in a readonly class,
     *   because it can lead to "initialized twice" errors in inheritance chains.
     */
    protected array $context;

    /**
     * Constructor
     *
     * @param ResultContract $result
     * @param JsonResource|ResourceCollection|Collection|null $data
     * @param array $context Runtime context overrides such as translation placeholders
     */
    public function __construct(
        public ResultContract                                  $result,
        public JsonResource|ResourceCollection|Collection|null $data = null,
        array                                                  $context = [],
    ) {
        // Initialize readonly property exactly once
        $this->context = $context;
    }

    /**
     * Resolve the final Context used by this Response.
     *
     * Merge strategy:
     * - Start with defaults from defaultContext()
     * - Apply runtime overrides from $context
     *
     * Runtime values always override defaults.
     */
    protected function getContext(): array
    {
        return array_merge($this->defaultContext(), $this->context);
    }

    /**
     * Return a new response instance with replaced context.
     *
     * This keeps the Response immutable.
     */
    public function withContext(array $context): static
    {
        return new static(
            $this->result,
            $this->data,
            $context
        );
    }

    /**
     * Return a new response instance with merged context.
     *
     * Existing context values will be preserved unless overridden.
     */
    public function withMergedContext(array $context): static
    {
        return new static(
            $this->result,
            $this->data,
            array_merge($this->context, $context)
        );
    }

    /**
     * Default Context for this Response subclass.
     *
     * Child classes may override this to define:
     * - Default translation placeholders
     * - Shared metadata used across multiple results
     * - Static values required by the response layer
     */
    protected function defaultContext(): array
    {
        return [];
    }

    /**
     * Get the HTTP status code associated with the operation result.
     *
     * @return int
     */
    public function statusCode(): int
    {
        return $this->result->statusCode();
    }

    /**
     * Get the user-facing feedback title associated with the operation result.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->result->title($this->getContext());
    }

    /**
     * Get the user-facing feedback message associated with the operation result.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->result->message($this->getContext());
    }

    /**
     * Get the user-facing feedback message associated with the operation result.
     *
     * @return Variant
     */
    public function variant(): Variant
    {
        return $this->result->variant();
    }

    /**
     * Determine if the operation was successful.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->result->isSuccess();
    }

    /**
     * Generate Toast
     * Actions & placeholders are handled in the Response الفرعي
     */
    public function toast(): ?Toast
    {
        return Toast::make(
            variant: $this->variant(),
            title: $this->title(),
            message: $this->message(),
            actions: $this->actionMap() ?? []
        );
    }

    /**
     * Each Response subclass defines its own Result -> Actions mapping
     */
    abstract protected function actionMap(): array;
}
