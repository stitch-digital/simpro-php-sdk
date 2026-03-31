<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\ContractorJobs\ContractorJobDetail;
use Simpro\PhpSdk\Simpro\Requests\ContractorJobs\GetContractorJobRequest;

it('sends get contractor job request to correct endpoint', function () {
    MockClient::global([
        GetContractorJobRequest::class => MockResponse::fixture('get_contractor_job_request'),
    ]);

    $request = new GetContractorJobRequest(0, 1630);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get contractor job response correctly', function () {
    MockClient::global([
        GetContractorJobRequest::class => MockResponse::fixture('get_contractor_job_request'),
    ]);

    $request = new GetContractorJobRequest(0, 1630);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(ContractorJobDetail::class)
        ->and($dto->id)->toBe(1630)
        ->and($dto->projectType)->toBe('Jobs')
        ->and($dto->contractor)->not->toBeNull()
        ->and($dto->contractor->id)->toBe(327)
        ->and($dto->contractor->name)->toBe('Marcus Hughes')
        ->and($dto->createdBy)->toBeNull()
        ->and($dto->status)->toBe('Pending')
        ->and($dto->description)->toBe('')
        ->and($dto->dateIssued)->toBe('2023-03-31')
        ->and($dto->dueDate)->toBe('')
        ->and($dto->contractorSupplyMaterials)->toBeFalse()
        ->and($dto->materials)->toBe(0.0)
        ->and($dto->currency)->toBe('GBP')
        ->and($dto->exchangeRate)->toBe(1.0)
        ->and($dto->labor)->toBeNull()
        ->and($dto->taxCode)->not->toBeNull()
        ->and($dto->taxCode->id)->toBe(3)
        ->and($dto->taxCode->code)->toBe('VAT')
        ->and($dto->taxCode->type)->toBe('Single')
        ->and($dto->taxCode->rate)->toBe(20.0)
        ->and($dto->retention)->not->toBeNull()
        ->and($dto->retention->amount)->toBe(0.0)
        ->and($dto->retention->perClaim)->toBe(0.0)
        ->and($dto->retention->periodMonths)->toBe(12)
        ->and($dto->total->exTax)->toBe(77.38)
        ->and($dto->total->incTax)->toBe(92.86)
        ->and($dto->customFields)->toBeArray()
        ->and($dto->customFields)->toBeEmpty()
        ->and($dto->dateModified)->not->toBeNull()
        ->and($dto->href)->toBe('/api/v1.0/companies/0/jobs/414786/sections/15311/costCenters/15615/contractorJobs/1630');
});
