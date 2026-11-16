<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\Sections\Section;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\GetSectionRequest;

it('sends get recurring invoice section request to correct endpoint', function () {
    MockClient::global([
        GetSectionRequest::class => MockResponse::fixture('get_recurring_invoice_section_request'),
    ]);

    $request = new GetSectionRequest(0, 501, 61);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/61');
});

it('parses get recurring invoice section response correctly', function () {
    MockClient::global([
        GetSectionRequest::class => MockResponse::fixture('get_recurring_invoice_section_request'),
    ]);

    $dto = $this->sdk->send(new GetSectionRequest(0, 501, 61))->dto();

    expect($dto)->toBeInstanceOf(Section::class)
        ->and($dto->id)->toBe(61)
        ->and($dto->name)->toBe('Monthly charges')
        ->and($dto->description)->toBe('Subscription and maintenance')
        ->and($dto->displayOrder)->toBe(1)
        ->and($dto->dateModified->format('Y-m-d'))->toBe('2026-11-16');
});
