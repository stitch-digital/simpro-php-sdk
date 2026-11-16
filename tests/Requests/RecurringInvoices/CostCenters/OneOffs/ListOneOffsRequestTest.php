<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Common\BillableStatus;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs\OneOffListItem;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\OneOffs\OneOffType;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\OneOffs\ListOneOffsRequest;

it('sends list recurring invoice oneOffs request to correct endpoint', function () {
    MockClient::global([
        ListOneOffsRequest::class => MockResponse::fixture('list_recurring_invoice_one_offs_request'),
    ]);

    $request = new ListOneOffsRequest(0, 501, 61, 7001);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7001/oneOffs/');
});

it('parses list recurring invoice oneOffs response correctly', function () {
    MockClient::global([
        ListOneOffsRequest::class => MockResponse::fixture('list_recurring_invoice_one_offs_request'),
    ]);

    $dto = $this->sdk->send(new ListOneOffsRequest(0, 501, 61, 7001))->dto();

    expect($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(OneOffListItem::class)
        ->and($dto[0]->id)->toBe(8101)
        ->and($dto[0]->type)->toBe(OneOffType::Material)
        ->and($dto[0]->billableStatus)->toBe(BillableStatus::Billable)
        ->and($dto[0]->description)->toBe('Qolsys subscription')
        ->and($dto[0]->sellPrice->incTax)->toBe(22.5)
        ->and($dto[0]->total->amount->exTax)->toBe(18.75);
});
