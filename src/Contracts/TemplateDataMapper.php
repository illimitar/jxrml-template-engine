<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\Contracts;

interface TemplateDataMapper
{
    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    public function parameters(mixed $source, array $config): array;

    /**
     * @param  array<string, mixed>  $config
     * @return iterable<int, array<string, mixed>|object>
     */
    public function rows(mixed $source, array $config): iterable;
}
