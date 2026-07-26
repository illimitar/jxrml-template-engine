<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\ValueObjects;

use A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper;

final readonly class RenderRequest
{
    /**
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>  $parameters
     * @param  null|iterable<int, array<string, mixed>|object>  $rows
     */
    public function __construct(
        public string $template,
        public mixed $source = null,
        public array $config = [],
        public ?TemplateDataMapper $mapper = null,
        public array $parameters = [],
        public ?iterable $rows = null,
        public string $output = 'S',
    ) {}
}
