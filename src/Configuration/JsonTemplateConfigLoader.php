<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\Configuration;

use A2Insights\JxrmlTemplateEngine\Exceptions\InvalidTemplateConfiguration;
use JsonException;

final class JsonTemplateConfigLoader
{
    /**
     * @return array<string, mixed>
     */
    public function load(string $path): array
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new InvalidTemplateConfiguration("Template configuration not found: {$path}");
        }

        try {
            $config = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidTemplateConfiguration(
                "Invalid template configuration at {$path}: {$exception->getMessage()}",
                previous: $exception,
            );
        }

        if (! is_array($config)) {
            throw new InvalidTemplateConfiguration("Template configuration must be a JSON object: {$path}");
        }

        return $config;
    }
}
