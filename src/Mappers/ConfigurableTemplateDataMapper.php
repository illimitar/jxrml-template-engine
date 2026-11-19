<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\Mappers;

use A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper;
use ArrayAccess;

class ConfigurableTemplateDataMapper implements TemplateDataMapper
{
    /**
     * @param  mixed  $source
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    public function parameters($source, array $config): array
    {
        $parameters = [];

        foreach ($config['parameters'] ?? [] as $key => $definition) {
            if (is_string($definition)) {
                $name = is_string($key) ? $key : $definition;
                $path = $definition;
                $default = null;
            } elseif (is_array($definition) && is_string($definition['name'] ?? null)) {
                $name = $definition['name'];
                $path = (string) ($definition['source'] ?? $name);
                $default = $definition['default'] ?? null;
            } else {
                continue;
            }

            $value = $this->value($source, $path, $default);
            $parameters[$name] = $this->mapParameter($name, $value, $source, $config);
        }

        return $parameters;
    }

    /**
     * @param  mixed  $source
     * @param  array<string, mixed>  $config
     * @return iterable<int, array<string, mixed>|object>
     */
    public function rows($source, array $config): iterable
    {
        $path = $config['datasource']['source'] ?? $config['rows'] ?? null;

        if (! is_string($path) || $path === '') {
            return [['id' => 1]];
        }

        $rows = $this->value($source, $path, []);

        return is_iterable($rows) ? $rows : [];
    }

    /**
     * Override this method to customize one parameter without replacing the
     * complete mapping algorithm.
     *
     * @param  mixed  $value
     * @param  mixed  $source
     * @param  array<string, mixed>  $config
     * @return mixed
     */
    protected function mapParameter(
        string $name,
        $value,
        $source,
        array $config
    ) {
        return $value;
    }

    /**
     * @param  mixed  $source
     * @param  mixed  $default
     * @return mixed
     */
    protected function value($source, string $path, $default = null)
    {
        if ($path === '') {
            return $source;
        }

        $value = $source;

        foreach (explode('.', $path) as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } elseif ($value instanceof ArrayAccess && $value->offsetExists($segment)) {
                $value = $value[$segment];
            } elseif (is_object($value) && isset($value->{$segment})) {
                $value = $value->{$segment};
            } elseif (is_object($value) && method_exists($value, $segment)) {
                $value = $value->{$segment}();
            } else {
                return $default;
            }
        }

        return $value;
    }
}
