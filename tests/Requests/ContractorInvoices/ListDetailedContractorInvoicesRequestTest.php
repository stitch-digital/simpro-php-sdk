<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\ContractorInvoices\ContractorInvoice;
use Simpro\PhpSdk\Simpro\Data\ContractorInvoices\ContractorInvoiceContractor;
use Simpro\PhpSdk\Simpro\Data\ContractorInvoices\ContractorInvoiceContractorBanking;
use Simpro\PhpSdk\Simpro\Data\Contractors\ContractorPaymentTerms;
use Simpro\PhpSdk\Simpro\Requests\ContractorInvoices\ListDetailedContractorInvoicesRequest;

it('sends list detailed contractor invoices request to correct endpoint', function () {
    MockClient::global([
        ListDetailedContractorInvoicesRequest::class => MockResponse::fixture('list_detailed_contractor_invoices_request'),
    ]);

    $request = new ListDetailedContractorInvoicesRequest(0);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses list detailed contractor invoices response correctly', function () {
    MockClient::global([
        ListDetailedContractorInvoicesRequest::class => MockResponse::fixture('list_detailed_contractor_invoices_request'),
    ]);

    $request = new ListDetailedContractorInvoicesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeArray()
        ->and($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(ContractorInvoice::class)
        ->and($dto[0]->id)->toBe(5001)
        ->and($dto[0]->contractorJobs)->toBe([30130, 29859])
        ->and($dto[0]->invoiceNo)->toBe('INV-001')
        ->and($dto[0]->currency)->toBe('GBP');
});

it('parses contractor invoice contractor banking with payment terms as object', function () {
    MockClient::global([
        ListDetailedContractorInvoicesRequest::class => MockResponse::fixture('list_detailed_contractor_invoices_request'),
    ]);

    $request = new ListDetailedContractorInvoicesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    $contractor = $dto[0]->contractor;
    expect($contractor)->toBeInstanceOf(ContractorInvoiceContractor::class)
        ->and($contractor->id)->toBe(4285)
        ->and($contractor->name)->toBe('Bright Spark Electrical Limited')
        ->and($contractor->banking)->toBeInstanceOf(ContractorInvoiceContractorBanking::class)
        ->and($contractor->banking->accountName)->toBe('Bright Spark Electrical')
        ->and($contractor->banking->routingNo)->toBe('20-30-40')
        ->and($contractor->banking->accountNo)->toBe('12345678')
        ->and($contractor->banking->paymentTermId)->toBe(934)
        ->and($contractor->banking->paymentTerms)->toBeInstanceOf(ContractorPaymentTerms::class)
        ->and($contractor->banking->paymentTerms->days)->toBe(30)
        ->and($contractor->banking->paymentTerms->type)->toBe('Invoice');
});

it('parses contractor invoice cost centers correctly', function () {
    MockClient::global([
        ListDetailedContractorInvoicesRequest::class => MockResponse::fixture('list_detailed_contractor_invoices_request'),
    ]);

    $request = new ListDetailedContractorInvoicesRequest(0);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto[0]->costCenters)->toHaveCount(1)
        ->and($dto[0]->costCenters[0]->contractorJob)->toBe(30130)
        ->and($dto[0]->costCenters[0]->costCenter->id)->toBe(500)
        ->and($dto[0]->costCenters[0]->costCenter->name)->toBe('P - Electrical')
        ->and($dto[0]->costCenters[0]->labour->exTax)->toBe(1000.0)
        ->and($dto[0]->costCenters[0]->material->exTax)->toBe(500.0);
});
