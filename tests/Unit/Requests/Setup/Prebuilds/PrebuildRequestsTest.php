<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Setup\Prebuilds\Prebuild;
use Simpro\PhpSdk\Simpro\Requests\Setup\Prebuilds\GetPrebuildRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\Prebuilds\ListPrebuildsRequest;

it('builds list endpoint with company id', function (): void {
    $request = new ListPrebuildsRequest(0);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/prebuilds/');
});

it('builds get endpoint with company + prebuild id', function (): void {
    $request = new GetPrebuildRequest(0, 18368);

    expect($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/prebuilds/18368/');
});

it('list dto factory returns array of Prebuild', function (): void {
    $request = new ListPrebuildsRequest(0);
    $response = Mockery::mock(\Saloon\Http\Response::class);
    $response->shouldReceive('json')->andReturn([
        ['ID' => 1, 'Name' => 'A', 'Materials' => 5, 'MaterialMarkup' => 0],
        ['ID' => 2, 'Name' => 'B', 'Materials' => 0, 'MaterialMarkup' => 0],
    ]);

    $dtos = $request->createDtoFromResponse($response);

    expect($dtos)->toHaveCount(2)
        ->and($dtos[0])->toBeInstanceOf(Prebuild::class)
        ->and($dtos[0]->id)->toBe(1)
        ->and($dtos[1]->id)->toBe(2);
});
