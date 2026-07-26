<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('the engine does not depend on application concerns')
    ->expect('A2Insights\JxrmlTemplateEngine')
    ->not->toUse([
        'Filament',
        'Illuminate\Database\Eloquent',
    ]);
