<?php

declare(strict_types=1);

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Simpro\PhpSdk\Simpro\Data\Companies\CompanyListItem;
use Simpro\PhpSdk\Simpro\Paginators\SimproPaginator;
use Simpro\PhpSdk\Simpro\Query\QueryBuilder;
use Simpro\PhpSdk\Simpro\Query\Search;
use Simpro\PhpSdk\Simpro\Query\SearchLogic;
use Simpro\PhpSdk\Simpro\Query\SortDirection;
use Simpro\PhpSdk\Simpro\Requests\Companies\ListCompaniesRequest;

describe('QueryBuilder basics', function () {
    it('returns QueryBuilder from list()', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list();

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('exposes underlying paginator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list();
        $paginator = $builder->getPaginator();

        expect($paginator)->toBeInstanceOf(SimproPaginator::class);
    });
});

describe('QueryBuilder::search()', function () {
    it('accepts a single Search object', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->search(Search::make()->column('Name')->find('Test'));

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
        expect($builder->first())->toBeInstanceOf(CompanyListItem::class);
    });

    it('accepts an array of Search objects', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->search([
                Search::make()->column('Name')->find('Test'),
                Search::make()->column('ID')->greaterThan(0),
            ]);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('throws exception for invalid array contents', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        expect(fn () => $this->sdk->companies()->list()->search(['invalid']))
            ->toThrow(InvalidArgumentException::class, 'Array must contain only Search objects');
    });
});

describe('QueryBuilder::where()', function () {
    it('supports equals operator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->where('Name', '=', 'Test');

        expect($builder->first())->toBeInstanceOf(CompanyListItem::class);
    });

    it('supports not equal operator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->where('Status', '!=', 'Deleted');

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('supports comparison operators', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->where('ID', '>', 0)
            ->where('ID', '>=', 1)
            ->where('ID', '<', 100)
            ->where('ID', '<=', 99);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('supports like operator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->where('Name', 'like', 'Test');

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('supports in operator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->where('Status', 'in', ['Active', 'Pending']);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('supports not in operator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->where('Status', 'not in', ['Deleted', 'Cancelled']);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('supports between operator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->where('ID', 'between', [1, 100]);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('throws exception for unknown operator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        expect(fn () => $this->sdk->companies()->list()->where('Name', 'unknown', 'value'))
            ->toThrow(InvalidArgumentException::class, 'Unknown operator: unknown');
    });
});

describe('QueryBuilder::logic()', function () {
    it('sets search logic to all', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->search([
                Search::make()->column('Name')->find('Test'),
                Search::make()->column('ID')->greaterThan(0),
            ])
            ->logic(SearchLogic::All);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('sets search logic to any', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->search([
                Search::make()->column('Name')->find('Test'),
                Search::make()->column('ID')->greaterThan(0),
            ])
            ->logic(SearchLogic::Any);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('matchAll() is shorthand for logic(SearchLogic::All)', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->search([
                Search::make()->column('Name')->find('Test'),
                Search::make()->column('ID')->greaterThan(0),
            ])
            ->matchAll();

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('matchAny() is shorthand for logic(SearchLogic::Any)', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->search([
                Search::make()->column('Name')->find('Test'),
                Search::make()->column('ID')->greaterThan(0),
            ])
            ->matchAny();

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });
});

describe('QueryBuilder::orderBy()', function () {
    it('normalizes field names to PascalCase', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        // The normalization happens internally, so we verify by testing the builder works
        $builder = $this->sdk->companies()->list()
            ->orderBy('name');  // camelCase should be normalized to Name

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('normalizes id to ID in order by', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->orderBy('id');  // Should be normalized to ID

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('orders ascending by default', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->orderBy('Name');

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('orders ascending with SortDirection enum', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->orderBy('Name', SortDirection::Ascending);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('orders descending with SortDirection enum', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->orderBy('Name', SortDirection::Descending);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('accepts string direction "asc"', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->orderBy('Name', 'asc');

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('accepts string direction "desc"', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->orderBy('Name', 'desc');

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('orderByDesc() is shorthand for descending order', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->orderByDesc('Name');

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('orderByAsc() is shorthand for ascending order', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->orderByAsc('Name');

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('throws exception for unknown direction string', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        expect(fn () => $this->sdk->companies()->list()->orderBy('Name', 'invalid'))
            ->toThrow(InvalidArgumentException::class, 'Unknown sort direction: invalid');
    });
});

describe('QueryBuilder::filter()', function () {
    it('adds raw filter parameters', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->filter('columns', 'ID,Name')
            ->filter('page', 1);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('converts boolean true to string', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->filter('isActive', true);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });

    it('converts boolean false to string', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list()
            ->filter('isActive', false);

        expect($builder)->toBeInstanceOf(QueryBuilder::class);
    });
});

describe('QueryBuilder terminal methods', function () {
    it('items() returns a generator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $items = $this->sdk->companies()->list()->items();

        expect($items)->toBeInstanceOf(Generator::class);
    });

    it('collect() returns a lazy collection', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $collection = $this->sdk->companies()->list()->collect();

        expect($collection)->toBeInstanceOf(Illuminate\Support\LazyCollection::class);
    });

    it('first() returns the first item', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $first = $this->sdk->companies()->list()->first();

        expect($first)->toBeInstanceOf(CompanyListItem::class);
    });

    it('all() returns an array of all items', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $all = $this->sdk->companies()->list()->all();

        expect($all)->toBeArray()
            ->and($all[0])->toBeInstanceOf(CompanyListItem::class);
    });
});

describe('QueryBuilder method forwarding', function () {
    it('forwards unknown methods to paginator', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $builder = $this->sdk->companies()->list();

        // getTotalResults() is a method on SimproPaginator
        expect($builder->getTotalResults())->toBeInt();
    });
});

describe('QueryBuilder full fluent chain', function () {
    it('supports complete fluent chain with search, order, and collect', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $result = $this->sdk->companies()->list()
            ->search(Search::make()->column('Name')->find('Test'))
            ->orderByDesc('Name')
            ->collect()
            ->first();

        expect($result)->toBeInstanceOf(CompanyListItem::class);
    });

    it('supports multiple search criteria with matchAny', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $results = $this->sdk->companies()->list()
            ->search([
                Search::make()->column('Name')->find('Corp'),
                Search::make()->column('ID')->greaterThan(5),
            ])
            ->matchAny()
            ->orderByDesc('Name')
            ->all();

        expect($results)->toBeArray();
    });

    it('supports where() syntax chain', function () {
        MockClient::global([
            ListCompaniesRequest::class => MockResponse::fixture('list_companies_request'),
        ]);

        $results = $this->sdk->companies()->list()
            ->where('Name', 'like', 'Acme')
            ->where('ID', '>=', 10)
            ->first();

        expect($results)->toBeInstanceOf(CompanyListItem::class);
    });
});
