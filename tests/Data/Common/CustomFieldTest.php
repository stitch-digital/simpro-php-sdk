<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Data\Common\CustomField;

it('creates custom field from flat array', function () {
    $field = CustomField::fromArray([
        'ID' => 1,
        'Name' => 'Project Code',
        'Type' => 'Text',
        'IsMandatory' => true,
        'Value' => 'PRJ-001',
    ]);

    expect($field->id)->toBe(1)
        ->and($field->name)->toBe('Project Code')
        ->and($field->type)->toBe('Text')
        ->and($field->isMandatory)->toBeTrue()
        ->and($field->value)->toBe('PRJ-001');
});

it('creates custom field from nested CustomField format', function () {
    $field = CustomField::fromArray([
        'CustomField' => [
            'ID' => 2,
            'Name' => 'Priority',
            'Type' => 'List',
            'IsMandatory' => false,
            'ListItems' => ['High', 'Medium', 'Low'],
        ],
        'Value' => 'High',
    ]);

    expect($field->id)->toBe(2)
        ->and($field->name)->toBe('Priority')
        ->and($field->type)->toBe('List')
        ->and($field->isMandatory)->toBeFalse()
        ->and($field->listItems)->toBe(['High', 'Medium', 'Low'])
        ->and($field->value)->toBe('High');
});

it('checks if field has value', function () {
    $withValue = CustomField::fromArray(['ID' => 1, 'Name' => 'Test', 'Value' => 'something']);
    $withNull = CustomField::fromArray(['ID' => 1, 'Name' => 'Test', 'Value' => null]);
    $withEmpty = CustomField::fromArray(['ID' => 1, 'Name' => 'Test', 'Value' => '']);

    expect($withValue->hasValue())->toBeTrue()
        ->and($withNull->hasValue())->toBeFalse()
        ->and($withEmpty->hasValue())->toBeFalse();
});

it('checks if field is list type', function () {
    $listType = CustomField::fromArray(['ID' => 1, 'Name' => 'Test', 'Type' => 'List']);
    $withListItems = CustomField::fromArray(['ID' => 2, 'Name' => 'Test', 'ListItems' => ['A', 'B']]);
    $textType = CustomField::fromArray(['ID' => 3, 'Name' => 'Test', 'Type' => 'Text']);

    expect($listType->isListType())->toBeTrue()
        ->and($withListItems->isListType())->toBeTrue()
        ->and($textType->isListType())->toBeFalse();
});
