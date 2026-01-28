<?php

declare(strict_types=1);

use Simpro\PhpSdk\Simpro\Query\Search;

describe('Search::make()', function () {
    it('creates a new Search instance', function () {
        $search = Search::make();

        expect($search)->toBeInstanceOf(Search::class);
    });
});

describe('Search::column()', function () {
    it('sets the column name', function () {
        $search = Search::make()->column('Name');

        expect($search->getColumn())->toBe('Name');
    });

    it('supports dot notation for nested fields', function () {
        $search = Search::make()->column('Address.City');

        expect($search->getColumn())->toBe('Address.City');
    });

    it('normalizes camelCase to PascalCase', function () {
        $search = Search::make()->column('name');

        expect($search->getColumn())->toBe('Name');
    });

    it('normalizes id to ID', function () {
        $search = Search::make()->column('id');

        expect($search->getColumn())->toBe('ID');
    });

    it('preserves ID when already uppercase', function () {
        $search = Search::make()->column('ID');

        expect($search->getColumn())->toBe('ID');
    });

    it('normalizes nested fields with dot notation', function () {
        $search = Search::make()->column('address.line1');

        expect($search->getColumn())->toBe('Address.Line1');
    });

    it('normalizes common abbreviations', function () {
        $abbreviations = [
            'uuid' => 'UUID',
            'ein' => 'EIN',
            'iban' => 'IBAN',
            'abn' => 'ABN',
            'acn' => 'ACN',
            'gst' => 'GST',
            'vat' => 'VAT',
            'url' => 'URL',
            'uri' => 'URI',
            'bsb' => 'BSB',
            'stc' => 'STC',
        ];

        foreach ($abbreviations as $input => $expected) {
            $search = Search::make()->column($input)->equals('Test');
            expect($search->getColumn())->toBe($expected);
        }
    });
});

describe('Search::equals()', function () {
    it('sets string value', function () {
        $search = Search::make()->column('Name')->equals('Test');

        expect($search->getValue())->toBe('Test');
    });

    it('sets integer value', function () {
        $search = Search::make()->column('ID')->equals(5);

        expect($search->getValue())->toBe('5');
    });

    it('sets float value', function () {
        $search = Search::make()->column('Price')->equals(19.99);

        expect($search->getValue())->toBe('19.99');
    });

    it('sets boolean true value', function () {
        $search = Search::make()->column('Active')->equals(true);

        expect($search->getValue())->toBe('true');
    });

    it('sets boolean false value', function () {
        $search = Search::make()->column('Active')->equals(false);

        expect($search->getValue())->toBe('false');
    });
});

describe('Search::find()', function () {
    it('wraps value with URL-encoded wildcards', function () {
        $search = Search::make()->column('Name')->find('Test');

        expect($search->getValue())->toBe('%25Test%25');
    });
});

describe('Search::like()', function () {
    it('is an alias for find', function () {
        $search = Search::make()->column('Name')->like('Test');

        expect($search->getValue())->toBe('%25Test%25');
    });
});

describe('Search::startsWith()', function () {
    it('adds trailing wildcard', function () {
        $search = Search::make()->column('Name')->startsWith('Test');

        expect($search->getValue())->toBe('Test%25');
    });
});

describe('Search::endsWith()', function () {
    it('adds leading wildcard', function () {
        $search = Search::make()->column('Name')->endsWith('Test');

        expect($search->getValue())->toBe('%25Test');
    });
});

describe('Search::lessThan()', function () {
    it('prefixes value with <', function () {
        $search = Search::make()->column('ID')->lessThan(10);

        expect($search->getValue())->toBe('<10');
    });
});

describe('Search::lessThanOrEqual()', function () {
    it('prefixes value with <=', function () {
        $search = Search::make()->column('ID')->lessThanOrEqual(10);

        expect($search->getValue())->toBe('<=10');
    });
});

describe('Search::greaterThan()', function () {
    it('prefixes value with >', function () {
        $search = Search::make()->column('ID')->greaterThan(10);

        expect($search->getValue())->toBe('>10');
    });
});

describe('Search::greaterThanOrEqual()', function () {
    it('prefixes value with >=', function () {
        $search = Search::make()->column('ID')->greaterThanOrEqual(10);

        expect($search->getValue())->toBe('>=10');
    });
});

describe('Search::notEqual()', function () {
    it('prefixes value with !=', function () {
        $search = Search::make()->column('Status')->notEqual('Cancelled');

        expect($search->getValue())->toBe('!=Cancelled');
    });

    it('handles null value', function () {
        $search = Search::make()->column('Status')->notEqual(null);

        expect($search->getValue())->toBe('!=null');
    });
});

describe('Search::between()', function () {
    it('formats range with tilde separator', function () {
        $search = Search::make()->column('ID')->between(1, 100);

        expect($search->getValue())->toBe('1~100');
    });
});

describe('Search::in()', function () {
    it('joins values with commas', function () {
        $search = Search::make()->column('Status')->in(['Active', 'Pending', 'New']);

        expect($search->getValue())->toBe('Active,Pending,New');
    });
});

describe('Search::notIn()', function () {
    it('prefixes each value with != and joins with commas', function () {
        $search = Search::make()->column('Status')->notIn(['Cancelled', 'Deleted']);

        expect($search->getValue())->toBe('!=Cancelled,!=Deleted');
    });
});

describe('Search::toQueryParam()', function () {
    it('returns array with column and value', function () {
        $search = Search::make()->column('Name')->equals('Test');
        [$column, $value] = $search->toQueryParam();

        expect($column)->toBe('Name')
            ->and($value)->toBe('Test');
    });

    it('throws exception if column is not set', function () {
        $search = Search::make()->equals('Test');

        expect(fn () => $search->toQueryParam())
            ->toThrow(InvalidArgumentException::class, 'Search column must be set');
    });

    it('throws exception if value is not set', function () {
        $search = Search::make()->column('Name');

        expect(fn () => $search->toQueryParam())
            ->toThrow(InvalidArgumentException::class, 'Search value must be set');
    });
});
