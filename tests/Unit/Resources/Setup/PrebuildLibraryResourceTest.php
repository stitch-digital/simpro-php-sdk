<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Connectors\SimproApiKeyConnector;
use Simpro\PhpSdk\Simpro\Data\Setup\Prebuilds\Prebuild;

it('lists prebuilds with columns + display=all baked in', function (): void {
    $mock = new MockClient([
        '*' => MockResponse::make([
            ['ID' => 18368, 'Name' => 'PFP - Roller Shutter Maintenance', 'Materials' => 0, 'MaterialMarkup' => 0],
        ], 200, ['Result-Total' => '1', 'Result-Count' => '1', 'Result-Pages' => '1']),
    ]);

    $connector = new SimproApiKeyConnector(baseUrl: 'https://example.simprosuite.com', apiKey: 'fake');
    $connector->withMockClient($mock);

    $results = $connector->prebuilds(0)->list()->all();

    expect($results)->toHaveCount(1)
        ->and($results[0])->toBeInstanceOf(Prebuild::class)
        ->and($results[0]->id)->toBe(18368);

    $request = $mock->getLastPendingRequest();
    $query = $request->query()->all();

    expect($query['columns'])->toContain('ID')
        ->and($query['columns'])->toContain('Materials')
        ->and($query['columns'])->toContain('MaterialMarkup')
        ->and($query['display'])->toBe('all');
});

it('gets a single prebuild by id', function (): void {
    $mock = new MockClient([
        '*' => MockResponse::make([
            'ID' => 18368,
            'Name' => 'PFP - Roller Shutter Maintenance',
        ], 200),
    ]);

    $connector = new SimproApiKeyConnector(baseUrl: 'https://example.simprosuite.com', apiKey: 'fake');
    $connector->withMockClient($mock);

    $prebuild = $connector->prebuilds(0)->get(18368);

    expect($prebuild->id)->toBe(18368)
        ->and($prebuild->name)->toBe('PFP - Roller Shutter Maintenance');
});
