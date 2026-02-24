<?php

namespace Sadeem\Core\Module\Http\Responders;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use InvalidArgumentException;
use Sadeem\Core\Module\Http\Responders\Options\ResponderOptions;

/**
 * Class Responder
 *
 * Base abstract responder that provides helper methods for formatting
 * and processing responses. All child responders must implement the `respond` method.
 */
abstract class Responder
{
    /**
     * Transform a BaseResponse + Options DTO into an HTTP response.
     *
     * Each child responder must implement this method to handle its specific Options DTO.
     *
     * @param ResponderOptions $options
     *   Strongly-typed options object. This ensures type safety for all responders.
     *
     * @return mixed
     *   The final HTTP response (JsonResponse, RedirectResponse, Inertia Response, etc.).
     */
    abstract public function respond(ResponderOptions $options): mixed;

    /**
     * Helper to format the response data (common logic for all responders)
     *
     * @param mixed $response
     * @return array
     */
    protected function formatResponse(mixed $response): array
    {
        return [
            'success' => $response->isSuccess(),
            'data' => $this->resolveData($response->data),
        ];
    }

    /**
     * Helper to merge extra metadata if needed
     *
     * @param array $responseData
     * @param array $extra
     * @return array
     */
    protected function mergeMeta(array $responseData, array $extra = []): array
    {
        return array_merge($responseData, $extra);
    }

    /**
     * Ensure that the given Inertia component exists in the Pages directory.
     *
     * This method checks for the existence of the component file
     * (Vue / JSX / TSX) before attempting to render it.
     *
     * @param string $component The Inertia component name (e.g. "Language/Index")
     *
     * @throws InvalidArgumentException If the component file does not exist
     */
    protected function ensureComponentExists(string $component): void
    {
        // Possible base paths for Inertia pages
        $basePaths = [
            resource_path('js/Pages'), // Main project
            base_path('modules'),      // Modules root
        ];
        // Possible component file extensions
        $extensions = ['vue', 'jsx', 'tsx'];
        foreach ($basePaths as $basePath) {
            foreach ($extensions as $ext) {
                // Case 1: Main Pages
                $projectFile = "{$basePath}/{$component}.{$ext}";
                if (is_file($projectFile)) {
                    return;
                }
                // Case 2: Module Pages (ModuleName::path/to/Page)
                if (str_contains($component, '::')) {
                    [$module, $path] = explode('::', $component, 2);

                    $moduleFile = "{$basePath}/{$module}/resources/js/pages/{$path}.{$ext}";
                    if (is_file($moduleFile)) {
                        return;
                    }
                }
            }
        }
        throw new InvalidArgumentException(
            "Inertia component [{$component}] was not found in Project or Module Pages."
        );
    }

    /**
     * Resolve response data into a frontend-safe format.
     *
     * @param mixed $data The raw data returned from the UseCase response
     * @return mixed Resolved data ready for the frontend
     */
    protected function resolveData(mixed $data): mixed
    {
        if (
            $data instanceof JsonResource || $data instanceof ResourceCollection
        ) {
            return $data->resolve();
        }
        return $data;
    }
}
