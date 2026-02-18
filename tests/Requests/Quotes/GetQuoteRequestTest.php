<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Quotes\Quote;
use Simpro\PhpSdk\Simpro\Data\Quotes\QuoteStatus;
use Simpro\PhpSdk\Simpro\Data\Quotes\QuoteTotal;
use Simpro\PhpSdk\Simpro\Requests\Quotes\GetQuoteRequest;

it('sends get quote request to correct endpoint', function () {
    MockClient::global([
        GetQuoteRequest::class => MockResponse::fixture('get_quote_request'),
    ]);

    $request = new GetQuoteRequest(0, 1);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get quote response correctly', function () {
    MockClient::global([
        GetQuoteRequest::class => MockResponse::fixture('get_quote_request'),
    ]);

    $request = new GetQuoteRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(Quote::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->type)->toBe('Project')
        ->and($dto->name)->toBe('Kitchen Quote')
        ->and($dto->site)->not->toBeNull()
        ->and($dto->site->id)->toBe(10)
        ->and($dto->customer)->not->toBeNull()
        ->and($dto->customer->companyName)->toBe('Acme Corp')
        ->and($dto->customer->givenName)->toBe('John')
        ->and($dto->customer->familyName)->toBe('Smith')
        ->and($dto->customerContact)->not->toBeNull()
        ->and($dto->customerContact->givenName)->toBe('Jane')
        ->and($dto->siteContact)->not->toBeNull()
        ->and($dto->siteContact->givenName)->toBe('Bob')
        ->and($dto->salesperson)->not->toBeNull()
        ->and($dto->salesperson->name)->toBe('Alice Sales')
        ->and($dto->projectManager)->not->toBeNull()
        ->and($dto->projectManager->name)->toBe('Bob Manager')
        ->and($dto->technicians)->toBeArray()
        ->and($dto->technicians)->toHaveCount(1)
        ->and($dto->status)->toBeInstanceOf(QuoteStatus::class)
        ->and($dto->status->name)->toBe('Approved')
        ->and($dto->status->color)->toBe('#00FF00')
        ->and($dto->stage)->toBe('Won')
        ->and($dto->orderNo)->toBe('QTE-001')
        ->and($dto->validityDays)->toBe(30)
        ->and($dto->isClosed)->toBeFalse()
        ->and($dto->isVariation)->toBeFalse()
        ->and($dto->forecast)->not->toBeNull()
        ->and($dto->forecast->year)->toBe(2024)
        ->and($dto->forecast->percent)->toBe(80)
        ->and($dto->total)->toBeInstanceOf(QuoteTotal::class)
        ->and($dto->total->incTax)->toBe(15000.00)
        ->and($dto->totals)->not->toBeNull()
        ->and($dto->totals->materialsCost)->not->toBeNull()
        ->and($dto->totals->materialsCost->estimate)->toBe(5000.00)
        ->and($dto->totals->discount)->toBe(500.00)
        ->and($dto->tags)->toBeArray()
        ->and($dto->tags)->toHaveCount(2)
        ->and($dto->autoAdjustStatus)->toBeTrue()
        ->and($dto->stc)->not->toBeNull()
        ->and($dto->stc->stcsEligible)->toBeFalse();
});
