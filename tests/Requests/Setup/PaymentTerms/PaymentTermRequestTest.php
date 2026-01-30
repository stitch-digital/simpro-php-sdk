<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\PaymentTerm;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms\GetPaymentTermRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentTerms\ListPaymentTermsRequest;

it('sends list payment terms request to correct endpoint', function () {
    MockClient::global([
        ListPaymentTermsRequest::class => MockResponse::fixture('list_payment_terms_request'),
    ]);

    $request = new ListPaymentTermsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list payment terms response correctly', function () {
    MockClient::global([
        ListPaymentTermsRequest::class => MockResponse::fixture('list_payment_terms_request'),
    ]);

    $request = new ListPaymentTermsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(3)
        ->and($dto[0])->toBeInstanceOf(PaymentTerm::class)
        ->and($dto[0]->paymentTermId)->toBe(1)
        ->and($dto[0]->paymentTermName)->toBe('Net 30')
        ->and($dto[0]->days)->toBe(30)
        ->and($dto[0]->type)->toBe('Invoice')
        ->and($dto[0]->isDefault)->toBeTrue()
        ->and($dto[2]->type)->toBe('Month');
});

it('parses get payment term response correctly', function () {
    MockClient::global([
        GetPaymentTermRequest::class => MockResponse::fixture('get_payment_term_request'),
    ]);

    $request = new GetPaymentTermRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(PaymentTerm::class)
        ->and($dto->paymentTermId)->toBe(1)
        ->and($dto->paymentTermName)->toBe('Net 30')
        ->and($dto->days)->toBe(30)
        ->and($dto->type)->toBe('Invoice')
        ->and($dto->isDefault)->toBeTrue();
});

it('can access payment terms via setup resource', function () {
    MockClient::global([
        ListPaymentTermsRequest::class => MockResponse::fixture('list_payment_terms_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->paymentTerms()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get payment term via setup resource', function () {
    MockClient::global([
        GetPaymentTermRequest::class => MockResponse::fixture('get_payment_term_request'),
    ]);

    $paymentTerm = $this->sdk->setup(0)->paymentTerms()->get(1);

    expect($paymentTerm)->toBeInstanceOf(PaymentTerm::class)
        ->and($paymentTerm->paymentTermId)->toBe(1);
});
