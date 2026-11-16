<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\CostCenter;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\CostCenters\CostCenterListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\CreateCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\DeleteCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\GetCostCenterRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\ListCostCentersRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\CostCenters\UpdateCostCenterRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\CostCenterResource;
use Simpro\PhpSdk\Simpro\Scopes\RecurringInvoices\CostCenterScope;

it('navigates from a section scope to its cost centers', function () {
    $section = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61);

    expect($section->costCenters())->toBeInstanceOf(CostCenterResource::class)
        ->and($section->costCenter(7001))->toBeInstanceOf(CostCenterScope::class);
});

it('lists and gets cost centers through the scope', function () {
    MockClient::global([
        ListCostCentersRequest::class => MockResponse::fixture('list_recurring_invoice_section_cost_centers_request'),
        GetCostCenterRequest::class => MockResponse::fixture('get_recurring_invoice_section_cost_center_request'),
    ]);

    $costCenters = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenters();

    expect($costCenters->list()->all()[0])->toBeInstanceOf(CostCenterListItem::class)
        ->and($costCenters->get(7001))->toBeInstanceOf(CostCenter::class);
});

it('creates, updates and deletes a cost center at the nested endpoint', function () {
    $mockClient = MockClient::global([
        CreateCostCenterRequest::class => MockResponse::make(['ID' => 7003], 201),
        UpdateCostCenterRequest::class => MockResponse::make([], 204),
        DeleteCostCenterRequest::class => MockResponse::make([], 204),
    ]);

    $costCenters = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61)->costCenters();

    expect($costCenters->create(['CostCenter' => 485]))->toBe(7003);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'POST'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/'
        && $request->body()->all() === ['CostCenter' => 485]);

    expect($costCenters->update(7003, ['ItemsLocked' => true])->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'PATCH'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7003');

    expect($costCenters->delete(7003)->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'DELETE'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/61/costCenters/7003');
});
