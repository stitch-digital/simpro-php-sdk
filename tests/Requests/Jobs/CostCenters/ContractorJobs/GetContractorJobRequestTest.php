<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Jobs\CostCenters\ContractorJobs\ContractorJob;
use Simpro\PhpSdk\Simpro\Requests\Jobs\CostCenters\ContractorJobs\GetContractorJobRequest;

it('sends get job contractor job request to correct endpoint', function () {
    MockClient::global([
        GetContractorJobRequest::class => MockResponse::fixture('get_job_contractor_job_request'),
    ]);

    $request = new GetContractorJobRequest(0, 414786, 15311, 15615, 1630);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200);
});

it('parses get job contractor job response correctly', function () {
    MockClient::global([
        GetContractorJobRequest::class => MockResponse::fixture('get_job_contractor_job_request'),
    ]);

    $request = new GetContractorJobRequest(0, 414786, 15311, 15615, 1630);
    $response = $this->sdk->send($request);
    $dto = $response->dto();

    expect($dto)->toBeInstanceOf(ContractorJob::class)
        ->and($dto->id)->toBe(1630)
        ->and($dto->projectType)->toBe('')
        ->and($dto->contractor)->not->toBeNull()
        ->and($dto->contractor->id)->toBe(327)
        ->and($dto->contractor->name)->toBe('Marcus Hughes')
        ->and($dto->createdBy)->toBeNull()
        ->and($dto->status)->toBe('Pending')
        ->and($dto->contractorSupplyMaterials)->toBeFalse()
        ->and($dto->currency)->toBe('GBP')
        ->and($dto->labor)->toBeNull()
        ->and($dto->taxCode->id)->toBe(3)
        ->and($dto->taxCode->code)->toBe('VAT')
        ->and($dto->retention->periodMonths)->toBe(12)
        ->and($dto->total->exTax)->toBe(0.0)
        ->and($dto->items)->not->toBeNull()
        ->and($dto->items->catalogs)->toHaveCount(1)
        ->and($dto->items->catalogs[0]->id)->toBe(1)
        ->and($dto->items->catalogs[0]->catalogId)->toBe(10)
        ->and($dto->items->catalogs[0]->catalogPartNo)->toBe('CAT-001')
        ->and($dto->items->catalogs[0]->catalogName)->toBe('Copper pipe')
        ->and($dto->items->catalogs[0]->priceLabor)->toBe(25.0)
        ->and($dto->items->catalogs[0]->priceMaterial)->toBe(10.5)
        ->and($dto->items->catalogs[0]->qtyAssigned)->toBe(5.0)
        ->and($dto->items->catalogs[0]->qtyRemaining)->toBe(3.0)
        ->and($dto->items->prebuilds)->toHaveCount(1)
        ->and($dto->items->prebuilds[0]->id)->toBe(2)
        ->and($dto->items->prebuilds[0]->prebuildId)->toBe(20)
        ->and($dto->items->prebuilds[0]->prebuildName)->toBe('Standard fitting kit')
        ->and($dto->items->prebuilds[0]->priceLabor)->toBe(15.0)
        ->and($dto->items->prebuilds[0]->qtyAssigned)->toBe(2.0)
        ->and($dto->customFields)->toBeEmpty()
        ->and($dto->dateModified)->not->toBeNull();
});
