<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine;

use A2Insights\JxrmlTemplateEngine\Configuration\JsonTemplateConfigLoader;
use A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper;
use A2Insights\JxrmlTemplateEngine\ValueObjects\RenderRequest;

final class JxrmlTemplateEngine
{
    public function __construct(
        private readonly JxrmlRenderer $renderer,
        private readonly JsonTemplateConfigLoader $configLoader,
    ) {}

    /**
     * @param  array<string, mixed>  $parameters
     * @param  null|iterable<int, array<string, mixed>|object>  $rows
     */
    public function render(
        string $template,
        mixed $source = null,
        string|array|null $config = null,
        ?TemplateDataMapper $mapper = null,
        array $parameters = [],
        ?iterable $rows = null,
    ): string {
        $resolvedConfig = is_string($config)
            ? $this->configLoader->load($config)
            : ($config ?? []);

        return $this->renderer->render(new RenderRequest(
            template: $template,
            source: $source,
            config: $resolvedConfig,
            mapper: $mapper,
            parameters: $parameters,
            rows: $rows,
        ));
    }
}
