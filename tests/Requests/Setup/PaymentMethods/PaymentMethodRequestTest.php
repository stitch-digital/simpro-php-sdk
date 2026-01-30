<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Setup\PaymentMethod;
use Simpro\PhpSdk\Simpro\Data\Setup\PaymentMethodListItem;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods\GetPaymentMethodRequest;
use Simpro\PhpSdk\Simpro\Requests\Setup\PaymentMethods\ListPaymentMethodsRequest;

it('sends list payment methods request to correct endpoint', function () {
    MockClient::global([
        ListPaymentMethodsRequest::class => MockResponse::fixture('list_payment_methods_request'),
    ]);

    $request = new ListPaymentMethodsRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list payment methods response correctly', function () {
    MockClient::global([
        ListPaymentMethodsRequest::class => MockResponse::fixture('list_payment_methods_request'),
    ]);

    $request = new ListPaymentMethodsRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(3)
        ->and($dto[0])->toBeInstanceOf(PaymentMethodListItem::class)
        ->and($dto[0]->id)->toBe(1)
        ->and($dto[0]->name)->toBe('Cash')
        ->and($dto[1]->name)->toBe('Credit Card')
        ->and($dto[2]->name)->toBe('Bank Transfer');
});

it('parses get payment method response correctly', function () {
    MockClient::global([
        GetPaymentMethodRequest::class => MockResponse::fixture('get_payment_method_request'),
    ]);

    $request = new GetPaymentMethodRequest(0, 1);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(PaymentMethod::class)
        ->and($dto->id)->toBe(1)
        ->and($dto->name)->toBe('Cash')
        ->and($dto->accountNo)->toBe('1001')
        ->and($dto->type)->toBe('Bank')
        ->and($dto->financeCharge)->toBe(0.0);
});

it('can access payment methods via setup resource', function () {
    MockClient::global([
        ListPaymentMethodsRequest::class => MockResponse::fixture('list_payment_methods_request'),
    ]);

    $queryBuilder = $this->sdk->setup(0)->paymentMethods()->list();

    expect($queryBuilder)->toBeInstanceOf(\Simpro\PhpSdk\Simpro\Query\QueryBuilder::class);
});

it('can get payment method via setup resource', function () {
    MockClient::global([
        GetPaymentMethodRequest::class => MockResponse::fixture('get_payment_method_request'),
    ]);

    $paymentMethod = $this->sdk->setup(0)->paymentMethods()->get(1);

    expect($paymentMethod)->toBeInstanceOf(PaymentMethod::class)
        ->and($paymentMethod->id)->toBe(1);
});
