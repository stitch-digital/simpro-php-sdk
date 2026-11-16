<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\Sections\Section;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\Sections\SectionListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\CreateSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\DeleteSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\GetSectionRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\ListSectionsRequest;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\UpdateSectionRequest;
use Simpro\PhpSdk\Simpro\Resources\RecurringInvoices\RecurringInvoiceSectionResource;
use Simpro\PhpSdk\Simpro\Scopes\RecurringInvoices\SectionScope;

it('navigates from a recurring invoice scope to its sections', function () {
    expect($this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->sections())
        ->toBeInstanceOf(RecurringInvoiceSectionResource::class)
        ->and($this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->section(61))
        ->toBeInstanceOf(SectionScope::class);
});

it('lists and gets sections through the scope', function () {
    MockClient::global([
        ListSectionsRequest::class => MockResponse::fixture('list_recurring_invoice_sections_request'),
        GetSectionRequest::class => MockResponse::fixture('get_recurring_invoice_section_request'),
    ]);

    $sections = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->sections();

    expect($sections->list()->all()[0])->toBeInstanceOf(SectionListItem::class)
        ->and($sections->get(61))->toBeInstanceOf(Section::class);
});

it('creates, updates and deletes a section at the nested endpoint', function () {
    $mockClient = MockClient::global([
        CreateSectionRequest::class => MockResponse::make(['ID' => 62], 201),
        UpdateSectionRequest::class => MockResponse::make([], 204),
        DeleteSectionRequest::class => MockResponse::make([], 204),
    ]);

    $sections = $this->sdk->recurringInvoices(companyId: 0)->recurringInvoice(501)->sections();

    expect($sections->create(['DisplayOrder' => 1]))->toBe(62);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'POST'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/'
        && $request->body()->all() === ['DisplayOrder' => 1]);

    expect($sections->update(62, ['Name' => 'Monthly'])->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'PATCH'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/62');

    expect($sections->delete(62)->status())->toBe(204);
    $mockClient->assertSent(fn (Request $request, Response $response) => $request->getMethod()->value === 'DELETE'
        && (string) $response->getPsrRequest()->getUri() === 'https://api.simpro.test/api/v1.0/companies/0/recurringInvoices/501/sections/62');
});
