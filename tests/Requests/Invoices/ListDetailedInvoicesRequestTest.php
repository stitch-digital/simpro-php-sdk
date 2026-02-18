<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Invoices\Invoice;
use Simpro\PhpSdk\Simpro\Requests\Invoices\ListDetailedInvoicesRequest;

it('sends list detailed invoices request to correct endpoint', function () {
    MockClient::global([
        ListDetailedInvoicesRequest::class => MockResponse::fixture('list_detailed_invoices_request'),
    ]);

    $request = new ListDetailedInvoicesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list detailed invoices response correctly', function () {
    MockClient::global([
        ListDetailedInvoicesRequest::class => MockResponse::fixture('list_detailed_invoices_request'),
    ]);

    $request = new ListDetailedInvoicesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(Invoice::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->internalId)->toBe('INV-001')
        ->and($dto[0]->type)->toBe('TaxInvoice')
        ->and($dto[0]->status->name)->toBe('Unpaid')
        ->and($dto[0]->total->balanceDue)->toBe(5000.00)
        ->and($dto[0]->currency)->toBe('AUD');
});
