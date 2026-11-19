<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine;

use A2Insights\JxrmlTemplateEngine\Configuration\JsonTemplateConfigLoader;
use A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper;
use A2Insights\JxrmlTemplateEngine\ValueObjects\RenderRequest;

final class JxrmlTemplateEngine
{
    /** @var JxrmlRenderer */
    private $renderer;

    /** @var JsonTemplateConfigLoader */
    private $configLoader;

    /**
     * @param JxrmlRenderer|null $renderer
     * @param JsonTemplateConfigLoader|null $configLoader
     */
    public function __construct(
        ?JxrmlRenderer $renderer = null,
        ?JsonTemplateConfigLoader $configLoader = null
    ) {
        $this->renderer = $renderer ?? new JxrmlRenderer();
        $this->configLoader = $configLoader ?? new JsonTemplateConfigLoader();
    }

    /**
     * @param  mixed  $source
     * @param  string|array|null  $config
     * @param  array<string, mixed>  $parameters
     * @param  null|iterable<int, array<string, mixed>|object>  $rows
     */
    public function render(
        string $template,
        $source = null,
        $config = null,
        ?TemplateDataMapper $mapper = null,
        array $parameters = [],
        ?iterable $rows = null
    ): string {
        $resolvedConfig = is_string($config)
            ? $this->configLoader->load($config)
            : ($config ?? []);

        return $this->renderer->render(new RenderRequest(
            $template,
            $source,
            $resolvedConfig,
            $mapper,
            $parameters,
            $rows
        ));
    }
}
