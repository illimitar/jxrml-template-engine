<?php

declare(strict_types=1);

use A2Insights\JxrmlTemplateEngine\Configuration\JsonTemplateConfigLoader;
use A2Insights\JxrmlTemplateEngine\Exceptions\InvalidTemplateConfiguration;

it('loads a JSON template configuration', function () {
    $path = tempnam(sys_get_temp_dir(), 'jxrml-config-');
    file_put_contents($path, '{"parameters":[{"name":"NUMERO","source":"numero"}]}');

    expect((new JsonTemplateConfigLoader)->load($path))
        ->toHaveKey('parameters.0.name', 'NUMERO');

    unlink($path);
});

it('rejects malformed JSON configurations', function () {
    $path = tempnam(sys_get_temp_dir(), 'jxrml-config-');
    file_put_contents($path, '{invalid');

    expect(fn () => (new JsonTemplateConfigLoader)->load($path))
        ->toThrow(InvalidTemplateConfiguration::class);

    unlink($path);
});
