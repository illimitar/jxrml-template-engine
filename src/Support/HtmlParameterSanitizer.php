<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\Support;

use DOMDocument;
use DOMXPath;

final class HtmlParameterSanitizer
{
    /**
     * @param  array<string, mixed>  $parameters
     * @return array<string, mixed>
     */
    public function sanitize(string $template, array $parameters): array
    {
        foreach ($this->htmlParameterNames($template) as $name) {
            if (is_string($parameters[$name] ?? null)) {
                $parameters[$name] = $this->sanitizeHtml($parameters[$name]);
            }
        }

        return $parameters;
    }

    /**
     * @return list<string>
     */
    private function htmlParameterNames(string $template): array
    {
        $dom = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $loaded = $dom->load($template, LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return [];
        }

        $names = [];
        $xpath = new DOMXPath($dom);
        $query = '//*[local-name()="textField"][*[local-name()="textElement"][@markup="html"]]/*[local-name()="textFieldExpression"]';

        foreach ($xpath->query($query) ?: [] as $expression) {
            preg_match_all('/\\$P\\{([A-Za-z0-9_]+)\\}/', $expression->textContent, $matches);
            $names = [...$names, ...$matches[1]];
        }

        return array_values(array_unique($names));
    }

    private function sanitizeHtml(string $html): string
    {
        $html = preg_replace('/<!--.*?-->/s', '', $html) ?? '';
        $html = preg_replace('/<\\s*(script|style)\\b[^>]*>.*?<\\s*\\/\\s*\\1\\s*>/is', '', $html) ?? '';
        $html = preg_replace('/\\s+on[a-z]+\\s*=\\s*(".*?"|\\\'.*?\\\'|[^\\s>]+)/is', '', $html) ?? '';
        $html = preg_replace('/\\s+(href|src)\\s*=\\s*("|\\\')?\\s*javascript:[^"\\\'>\\s]*(\\2)?/is', '', $html) ?? '';
        $html = strip_tags(
            $html,
            '<h1><h2><h3><h4><h5><h6><p><br><b><strong><i><em><u><s><del><sub><sup><ul><ol><li><blockquote><table><tbody><thead><tfoot><tr><td><th><div><span>',
        );

        return preg_replace('/<([a-z][a-z0-9]*)\\b[^>]*>/i', '<$1>', $html) ?? '';
    }
}
