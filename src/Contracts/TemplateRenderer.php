<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\Contracts;

use A2Insights\JxrmlTemplateEngine\ValueObjects\RenderRequest;

interface TemplateRenderer
{
    public function render(RenderRequest $request): string;
}
