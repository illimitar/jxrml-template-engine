<?php

declare(strict_types=1);

use A2Insights\JxrmlTemplateEngine\Mappers\ConfigurableTemplateDataMapper;

it('maps parameters and rows declared by the template configuration', function () {
    $source = [
        'prestador' => ['nome' => 'Empresa Exemplo'],
        'servicos' => [
            ['descricao' => 'Consultoria', 'valor' => 150],
        ],
    ];

    $config = [
        'parameters' => [
            ['name' => 'PRESTADOR_NOME', 'source' => 'prestador.nome'],
            ['name' => 'MUNICIPIO', 'source' => 'prestador.municipio', 'default' => 'Não informado'],
        ],
        'datasource' => ['source' => 'servicos'],
    ];

    $mapper = new ConfigurableTemplateDataMapper;

    expect($mapper->parameters($source, $config))->toBe([
        'PRESTADOR_NOME' => 'Empresa Exemplo',
        'MUNICIPIO' => 'Não informado',
    ])->and($mapper->rows($source, $config))->toBe($source['servicos']);
});

it('allows consumers to customize a single mapped parameter', function () {
    $mapper = new class extends ConfigurableTemplateDataMapper
    {
        protected function mapParameter(string $name, mixed $value, mixed $source, array $config): mixed
        {
            return $name === 'VALOR_TOTAL'
                ? 'R$ '.number_format((float) $value, 2, ',', '.')
                : $value;
        }
    };

    $parameters = $mapper->parameters(
        ['valor' => 1234.5, 'numero' => '42'],
        ['parameters' => [
            ['name' => 'VALOR_TOTAL', 'source' => 'valor'],
            ['name' => 'NUMERO', 'source' => 'numero'],
        ]],
    );

    expect($parameters)->toBe([
        'VALOR_TOTAL' => 'R$ 1.234,50',
        'NUMERO' => '42',
    ]);
});
