<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\Sections\SectionListItem;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\Sections\ListSectionsRequest;

it('sends list recurring invoice sections request to correct endpoint', function () {
    MockClient::global([
        ListSectionsRequest::class => MockResponse::fixture('list_recurring_invoice_sections_request'),
    ]);

    $request = new ListSectionsRequest(0, 501);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501/sections/');
});

it('parses list recurring invoice sections response correctly', function () {
    MockClient::global([
        ListSectionsRequest::class => MockResponse::fixture('list_recurring_invoice_sections_request'),
    ]);

    $dto = $this->sdk->send(new ListSectionsRequest(0, 501))->dto();

    expect($dto)->toHaveCount(1)
        ->and($dto[0])->toBeInstanceOf(SectionListItem::class)
        ->and($dto[0]->id)->toBe(61)
        ->and($dto[0]->name)->toBe('')
        ->and($dto[0]->displayOrder)->toBe(1);
});
