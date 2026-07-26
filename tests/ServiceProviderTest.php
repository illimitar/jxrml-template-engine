<?php

declare(strict_types=1);

use A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper;
use A2Insights\JxrmlTemplateEngine\Contracts\TemplateRenderer;
use A2Insights\JxrmlTemplateEngine\JxrmlRenderer;
use A2Insights\JxrmlTemplateEngine\JxrmlTemplateEngine;
use A2Insights\JxrmlTemplateEngine\Mappers\ConfigurableTemplateDataMapper;

it('registers the engine contracts in Laravel', function () {
    expect(app(TemplateDataMapper::class))->toBeInstanceOf(ConfigurableTemplateDataMapper::class)
        ->and(app(TemplateRenderer::class))->toBeInstanceOf(JxrmlRenderer::class)
        ->and(app(JxrmlTemplateEngine::class))->toBeInstanceOf(JxrmlTemplateEngine::class);
});
