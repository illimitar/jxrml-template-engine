# JXRML Template Engine

Engine PHP para renderizar templates JRXML sem dependência de Filament, Eloquent
ou de uma aplicação específica.

## Instalação

```bash
composer require a2insights/jxrml-template-engine
```

Em aplicações Laravel, o provider e a facade são descobertos automaticamente.

## Uso

Um template pode ser acompanhado por um arquivo JSON:

```json
{
    "parameters": [
        {
            "name": "NUMERO_NFSE",
            "source": "numero"
        },
        {
            "name": "PRESTADOR_NOME",
            "source": "prestador.nome",
            "default": ""
        }
    ],
    "datasource": {
        "type": "array",
        "source": "itens"
    }
}
```

Renderização:

```php
use A2Insights\JxrmlTemplateEngine\JxrmlTemplateEngine;

$pdf = app(JxrmlTemplateEngine::class)->render(
    template: resource_path('templates/danfse.jrxml'),
    config: resource_path('templates/danfse.json'),
    source: $nfse,
);
```

Parâmetros informados diretamente têm precedência sobre os produzidos pelo
mapper:

```php
$pdf = $engine->render(
    template: $template,
    source: $nfse,
    config: $config,
    parameters: ['PRESTADOR_NOME' => 'Nome substituído'],
);
```

## Mapper customizado

O mapper padrão aceita arrays, objetos, `ArrayAccess` e caminhos com pontos. Um
consumidor pode alterar apenas parâmetros específicos:

```php
use A2Insights\JxrmlTemplateEngine\Mappers\ConfigurableTemplateDataMapper;

final class DanfseTemplateMapper extends ConfigurableTemplateDataMapper
{
    protected function mapParameter(
        string $name,
        mixed $value,
        mixed $source,
        array $config,
    ): mixed {
        return match ($name) {
            'VALOR_TOTAL' => number_format((float) $value, 2, ',', '.'),
            default => $value,
        };
    }
}

$pdf = $engine->render(
    template: $template,
    config: $config,
    source: $nfse,
    mapper: new DanfseTemplateMapper,
);
```

Para controle completo, implemente
`A2Insights\JxrmlTemplateEngine\Contracts\TemplateDataMapper`.

## Configuração Laravel

```bash
php artisan vendor:publish --tag="jxrml-template-engine-config"
```

O arquivo publicado permite configurar um diretório de fontes e uma
implementação customizada do processador JasperPHP:

```php
return [
    'font_directory' => storage_path('app/fonts'),
    'processor' => App\Jasper\PdfProcessor::class,
];
```

## Testes

```bash
composer test
composer analyse
```
