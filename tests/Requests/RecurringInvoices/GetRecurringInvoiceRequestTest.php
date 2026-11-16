<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\InvoiceDatePeriod;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringFrequency;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoice;
use Simpro\PhpSdk\Simpro\Data\RecurringInvoices\RecurringInvoiceType;
use Simpro\PhpSdk\Simpro\Requests\RecurringInvoices\GetRecurringInvoiceRequest;

it('sends get recurring invoice request to correct endpoint', function () {
    MockClient::global([
        GetRecurringInvoiceRequest::class => MockResponse::fixture('get_recurring_invoice_request'),
    ]);

    $request = new GetRecurringInvoiceRequest(0, 501);
    $response = $this->sdk->send($request);

    expect($response->status())->toBe(200)
        ->and($request->resolveEndpoint())->toBe('/api/v1.0/companies/0/recurringInvoices/501');
});

it('parses get recurring invoice response correctly', function () {
    MockClient::global([
        GetRecurringInvoiceRequest::class => MockResponse::fixture('get_recurring_invoice_request'),
    ]);

    $dto = $this->sdk->send(new GetRecurringInvoiceRequest(0, 501))->dto();

    expect($dto)->toBeInstanceOf(RecurringInvoice::class)
        ->and($dto->id)->toBe(501)
        ->and($dto->type)->toBe(RecurringInvoiceType::Service)
        ->and($dto->customer->id)->toBe(2048)
        ->and($dto->customer->givenName)->toBe('Priya')
        ->and($dto->customerContract->id)->toBe(77)
        ->and($dto->customerContract->contractNo)->toBe('QS-0042')
        ->and($dto->customerContract->endDate)->toBeNull()
        ->and($dto->customerContact->familyName)->toBe('Nair')
        ->and($dto->additionalContacts)->toHaveCount(1)
        ->and($dto->additionalContacts[0]->givenName)->toBe('Dev')
        ->and($dto->site->id)->toBe(9001)
        ->and($dto->siteContact)->toBeNull()
        ->and($dto->name)->toBe('Qolsys subscription')
        ->and($dto->orderNo)->toBe('PO-1')
        ->and($dto->recurringStartDate->format('Y-m-d'))->toBe('2026-12-01')
        ->and($dto->nextRecurringDate->format('Y-m-d'))->toBe('2027-01-01')
        ->and($dto->recurringQuantity)->toBe(1)
        ->and($dto->invoiceDatePeriod)->toBe(InvoiceDatePeriod::Advance)
        ->and($dto->recurringFrequency)->toBe(RecurringFrequency::Month)
        ->and($dto->tags[0]->name)->toBe('Qolsys household')
        ->and($dto->paymentTermId)->toBe(3)
        ->and($dto->paymentTerms->days)->toBe(14)
        ->and($dto->paymentTerms->type)->toBe('Invoice')
        ->and($dto->salesperson->name)->toBe('Emma Harper')
        ->and($dto->projectManager)->toBeNull()
        ->and($dto->technicians[0]->id)->toBe(21)
        ->and($dto->technician)->toBeNull()
        ->and($dto->pricingTier->defaultMarkup)->toBe(25.0)
        ->and($dto->laborOverhead)->toBe(0.0)
        ->and($dto->laborType->name)->toBe('Standard Labour')
        ->and($dto->laborFitTime->multiplier)->toBe(1.0)
        ->and($dto->itemTaxCode->code)->toBe('VAT20')
        ->and($dto->labourTaxCode->rate)->toBe(20.0)
        ->and($dto->materialMarkup)->toBeNull()
        ->and($dto->inflationRate)->toBe(4.0)
        ->and($dto->inflationAnniversaryDate->format('Y-m-d'))->toBe('2027-12-01')
        ->and($dto->dateModified->format('Y-m-d'))->toBe('2026-11-16')
        ->and($dto->total->incTax)->toBe(35.0)
        ->and($dto->totals->materialsCost->estimate)->toBe(12.0)
        ->and($dto->customFields[0]->name)->toBe('Cover')
        ->and($dto->customFields[0]->value)->toBe('Subscription with maintenance')
        ->and($dto->archived)->toBeFalse()
        ->and($dto->removed)->toBeFalse();
});
