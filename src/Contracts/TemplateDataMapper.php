<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\Contracts;

interface TemplateDataMapper
{
    /**
     * @param  mixed  $source
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    public function parameters($source, array $config): array;

    /**
     * @param  mixed  $source
     * @param  array<string, mixed>  $config
     * @return iterable<int, array<string, mixed>|object>
     */
    public function rows($source, array $config): iterable;
}
