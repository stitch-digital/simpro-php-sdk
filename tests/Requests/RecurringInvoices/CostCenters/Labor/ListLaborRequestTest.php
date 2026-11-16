<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\Labor\LaborListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\Labor\ListLaborRequest;

it('sends list recurring invoice labor request to correct endpoint', function () {
    MockClient::global([
        ListLaborRequest::class => MockResponse::fixture('list_recurring_invoice_labors_request'),
    ]);

    $request = new ListLaborRequest(0, 501, 61, 7001);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/labor/');
});

it('parses list recurring invoice labor response correctly', function () {
    MockClient::global([
        ListLaborRequest::class => MockResponse::fixture('list_recurring_invoice_labors_request'),
    ]);

    $dto = $this->sdk->send(new ListLaborRequest(0, 501, 61, 7001))->dto();

    expect($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(LaborListItem::class)
        ->and($dto[0]->id)->toBe(8401)
        ->and($dto[0]->laborType->id)->toBe(4)
        ->and($dto[0]->sellPrice->exTax)->toBe(18.75);
});
