<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine;

use A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper;
use A2Insights\JxrmlTemplateEngine\Contracts\TemplateRenderer;
use A2Insights\JxrmlTemplateEngine\Mappers\ConfigurableTemplateDataMapper;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class JxrmlTemplateEngineServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('jxrml-template-engine')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->bind(TemplateDataMapper::class, ConfigurableTemplateDataMapper::class);
        $this->app->bind(TemplateRenderer::class, JxrmlRenderer::class);

        $this->app->singleton(JxrmlRenderer::class, fn (): JxrmlRenderer => new JxrmlRenderer(
            defaultMapper: $this->app->make(TemplateDataMapper::class),
            fontDirectory: config('jxrml-template-engine.font_directory'),
            processor: config('jxrml-template-engine.processor'),
        ));

        $this->app->singleton(JxrmlTemplateEngine::class);
    }
}
