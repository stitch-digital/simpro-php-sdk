<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Invoices\Invoice;
use Simpro\PhpSdk\Simpro\Requests\Invoices\GetInvoiceRequest;

it('sends get invoice request to correct endpoint', function () {
    MockClient::global([
        GetInvoiceRequest::class => MockResponse::fixture('get_invoice_request'),
    ]);

    $request = new GetInvoiceRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get invoice response correctly', function () {
    MockClient::global([
        GetInvoiceRequest::class => MockResponse::fixture('get_invoice_request'),
    ]);

    $request = new GetInvoiceRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Invoice::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->internalId)->toBe('INV-001')
        ->and($dto->type)->toBe('TaxInvoice')
        ->and($dto->customer)->not->toBeNull()
        ->and($dto->customer->companyName)->toBe('Acme Corp')
        ->and($dto->customer->givenName)->toBe('John')
        ->and($dto->customer->familyName)->toBe('Smith')
        ->and($dto->jobs)->toHaveCount(1)
        ->and($dto->jobs[0]->id)->toBe(100)
        ->and($dto->status)->not->toBeNull()
        ->and($dto->status->id)->toBe(1)
        ->and($dto->status->name)->toBe('Unpaid')
        ->and($dto->stage)->toBe('Approved')
        ->and($dto->orderNo)->toBe('ORD-001')
        ->and($dto->description)->toBe('Kitchen Renovation - Progress Claim 1')
        ->and($dto->total)->not->toBeNull()
        ->and($dto->total->incTax)->toBe(5000.00)
        ->and($dto->total->balanceDue)->toBe(5000.00)
        ->and($dto->total->amountApplied)->toBe(0.00)
        ->and($dto->isPaid)->toBeFalse()
        ->and($dto->currency)->toBe('AUD')
        ->and($dto->costCenters)->toHaveCount(1)
        ->and($dto->costCenters[0]->id)->toBe(1)
        ->and($dto->costCenters[0]->name)->toBe('Labor')
        ->and($dto->costCenters[0]->items)->toHaveCount(1)
        ->and($dto->costCenters[0]->items[0]->item->name)->toBe('Standard Labor');
});
