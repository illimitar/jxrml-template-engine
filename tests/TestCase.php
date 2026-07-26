<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\Tests;

use A2Insights\JxrmlTemplateEngine\JxrmlTemplateEngineServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            JxrmlTemplateEngineServiceProvider::class,
        ];
    }
}
