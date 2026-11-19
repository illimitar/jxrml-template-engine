<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\ValueObjects;

use A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper;

final class RenderRequest
{
    /** @var string */
    public $template;

    /** @var mixed */
    public $source;

    /** @var array<string, mixed> */
    public $config;

    /** @var TemplateDataMapper|null */
    public $mapper;

    /** @var array<string, mixed> */
    public $parameters;

    /** @var null|iterable<int, array<string, mixed>|object> */
    public $rows;

    /** @var string */
    public $output;

    /**
     * @param  mixed  $source
     * @param  array<string, mixed>  $config
     * @param  array<string, mixed>  $parameters
     * @param  null|iterable<int, array<string, mixed>|object>  $rows
     */
    public function __construct(
        string $template,
        $source = null,
        array $config = [],
        ?TemplateDataMapper $mapper = null,
        array $parameters = [],
        ?iterable $rows = null,
        string $output = 'S'
    ) {
        $this->template = $template;
        $this->source = $source;
        $this->config = $config;
        $this->mapper = $mapper;
        $this->parameters = $parameters;
        $this->rows = $rows;
        $this->output = $output;
    }
}
