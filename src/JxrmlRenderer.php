<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine;

use A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper;
use A2Insights\JxrmlTemplateEngine\Contracts\TemplateRenderer;
use A2Insights\JxrmlTemplateEngine\Exceptions\TemplateNotFound;
use A2Insights\JxrmlTemplateEngine\Mappers\ConfigurableTemplateDataMapper;
use A2Insights\JxrmlTemplateEngine\Support\HtmlParameterSanitizer;
use A2Insights\JxrmlTemplateEngine\ValueObjects\RenderRequest;
use JasperPHP\core\Instructions;
use JasperPHP\core\TJasper;
use JasperPHP\elements\Report;

final class JxrmlRenderer implements TemplateRenderer
{
    public function __construct(
        private readonly TemplateDataMapper $defaultMapper = new ConfigurableTemplateDataMapper,
        private readonly HtmlParameterSanitizer $sanitizer = new HtmlParameterSanitizer,
        private readonly ?string $fontDirectory = null,
        private readonly ?string $processor = null,
    ) {}

    public function render(RenderRequest $request): string
    {
        $template = realpath($request->template);

        if ($template === false || ! is_file($template)) {
            throw new TemplateNotFound("JRXML template not found: {$request->template}");
        }

        $this->configureJasper();

        $mapper = $request->mapper ?? $this->defaultMapper;
        $mappedParameters = $mapper->parameters($request->source, $request->config);
        $parameters = [...$mappedParameters, ...$request->parameters];
        $parameters = $this->sanitizer->sanitize($template, $parameters);

        $rows = $request->rows ?? $mapper->rows($request->source, $request->config);
        $dataSource = $this->dataSource($rows, $request->config);

        Report::$defaultFolder = dirname($template);

        return (new TJasper($template, $parameters, $dataSource))->output($request->output);
    }

    private function configureJasper(): void
    {
        if ($this->fontDirectory !== null && $this->fontDirectory !== '') {
            Instructions::$fontdir = rtrim($this->fontDirectory, DIRECTORY_SEPARATOR);
        }

        if ($this->processor !== null && $this->processor !== '') {
            Instructions::setProcessor($this->processor);
        }
    }

    /**
     * @param  iterable<int, array<string, mixed>|object>  $rows
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    private function dataSource(iterable $rows, array $config): array
    {
        $dataSource = $config['datasource'] ?? [];
        $type = is_array($dataSource) ? (string) ($dataSource['type'] ?? 'array') : 'array';

        if (in_array($type, ['json_file', 'csv_file'], true)) {
            $path = $dataSource['path'] ?? null;

            if (! is_string($path) || ! is_file($path)) {
                throw new TemplateNotFound("Datasource file not found: {$path}");
            }

            return ['type' => $type, 'path' => realpath($path)];
        }

        $normalized = is_array($rows) ? $rows : iterator_to_array($rows);

        return [
            'type' => 'array',
            'data' => $normalized === [] ? [['id' => 1]] : array_values($normalized),
        ];
    }
}
