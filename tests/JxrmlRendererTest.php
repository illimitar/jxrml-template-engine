<?php

declare(strict_types=1);

use A2Insights\JxrmlTemplateEngine\JxrmlRenderer;
use A2Insights\JxrmlTemplateEngine\ValueObjects\RenderRequest;

it('renders a JRXML template to a PDF string', function () {
    $pdf = (new JxrmlRenderer)->render(new RenderRequest(
        template: __DIR__.'/Fixtures/basic.jrxml',
        parameters: ['TITLE' => 'Sample document'],
        rows: [['description' => 'First row']],
    ));

    expect($pdf)->toStartWith('%PDF-');
});
