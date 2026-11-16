<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Prebuilds\PrebuildListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Prebuilds\ListPrebuildsRequest;

it('sends list recurring invoice prebuilds request to correct endpoint', function () {
    MockClient::global([
        ListPrebuildsRequest::class => MockResponse::fixture('list_recurring_invoice_prebuilds_request'),
    ]);

    $request = new ListPrebuildsRequest(0, 501, 61, 7001);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/prebuilds/');
});

it('parses list recurring invoice prebuilds response correctly', function () {
    MockClient::global([
        ListPrebuildsRequest::class => MockResponse::fixture('list_recurring_invoice_prebuilds_request'),
    ]);

    $dto = $this->sdk->send(new ListPrebuildsRequest(0, 501, 61, 7001))->dto();

    expect($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(PrebuildListItem::class)
        ->and($dto[0]->id)->toBe(8501)
        ->and($dto[0]->prebuild->name)->toBe('Qolsys starter kit')
        ->and($dto[0]->total->amount->exTax)->toBe(18.75);
});
