<?php

declare(strict_types=1);

namespace A2Insights\JxrmlTemplateEngine\Facades;

use Illuminate\Support\Facades\Facade;

final class JxrmlTemplateEngine extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \A2Insights\JxrmlTemplateEngine\JxrmlTemplateEngine::class;
    }
}
