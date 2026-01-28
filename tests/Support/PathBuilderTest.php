<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Support\PathBuilder;

it('builds simple company path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0');
});

it('builds job path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->jobs(123)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/jobs/123');
});

it('builds job list path with trailing slash', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->jobs()
        ->build(trailingSlash: true);

    expect($path)->toBe('/api/v1.0/companies/0/jobs/');
});

it('builds nested section and cost center path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->jobs(123)
        ->sections(1)
        ->costCenters(5)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/jobs/123/sections/1/costCenters/5');
});

it('builds attachment files path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->jobs(123)
        ->attachmentFiles(456)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/jobs/123/attachments/files/456');
});

it('builds attachment folders path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->jobs(123)
        ->attachmentFolders()
        ->build(trailingSlash: true);

    expect($path)->toBe('/api/v1.0/companies/0/jobs/123/attachments/folders/');
});

it('builds custom fields path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->jobs(123)
        ->customFields(7)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/jobs/123/customFields/7');
});

it('builds notes path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->quotes(99)
        ->notes()
        ->build(trailingSlash: true);

    expect($path)->toBe('/api/v1.0/companies/0/quotes/99/notes/');
});

it('builds customer path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->customers(50)
        ->contacts()
        ->build(trailingSlash: true);

    expect($path)->toBe('/api/v1.0/companies/0/customers/50/contacts/');
});

it('builds with generic segment', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->segment('customEndpoint', 123)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/customEndpoint/123');
});

it('can be cast to string', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->jobs(123);

    expect((string) $path)->toBe('/api/v1.0/companies/0/jobs/123');
});

it('builds invoice path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->invoices(500)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/invoices/500');
});

it('builds schedule path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->schedules(200)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/schedules/200');
});

it('builds employee path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->employees(15)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/employees/15');
});

it('builds site path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->sites(30)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/sites/30');
});

it('builds vendor path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->vendors(25)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/vendors/25');
});

it('builds catalog path', function () {
    $path = PathBuilder::make()
        ->companies(0)
        ->catalogs(100)
        ->build();

    expect($path)->toBe('/api/v1.0/companies/0/catalogs/100');
});
