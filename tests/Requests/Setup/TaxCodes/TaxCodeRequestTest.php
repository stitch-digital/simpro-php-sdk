<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\TaxCode;
use Simpro\PhpSdk\Simpro\Requests\Setup\TaxCodes\ListTaxCodesRequest;

it('sends list tax codes request to correct endpoint', function () {
    MockClient::global([
        ListTaxCodesRequest::class => MockResponse::fixture('list_tax_codes_request'),
    ]);

    $request = new ListTaxCodesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list tax codes response correctly', function () {
    MockClient::global([
        ListTaxCodesRequest::class => MockResponse::fixture('list_tax_codes_request'),
    ]);

    $request = new ListTaxCodesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(3)
        ->and($dto[0])->toBeInstanceOf(TaxCode::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->code)->toBe('GST')
        ->and($dto[0]->type)->toBe('Single')
        ->and($dto[0]->rate)->toBe(10.0)
        ->and($dto[2]->code)->toBe('VAT')
        ->and($dto[2]->type)->toBe('Combine')
        ->and($dto[2]->rate)->toBe(20.0);
});

it('can access tax codes via setup resource', function () {
    MockClient::global([
        ListTaxCodesRequest::class => MockResponse::fixture('list_tax_codes_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->taxCodes()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});
