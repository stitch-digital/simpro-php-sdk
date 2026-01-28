<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Query;

use InvalidArgumentException;

/**
 * Fluent builder for individual search criteria.
 *
 * @example
 * // Simple wildcard search
 * Search::make()->column('Name')->find('Test')
 *
 * // Exact match
 * Search::make()->column('ID')->equals(5)
 *
 * // Range comparison
 * Search::make()->column('ID')->greaterThan(10)
 *
 * // Dot notation for nested fields
 * Search::make()->column('Address.City')->equals('Brisbane')
 */
final class Search
{
    private ?string $column = null;

    private ?string $value = null;

    /**
     * Create a new Search instance.
     */
    public static function make(): self
    {
        return new self;
    }

    /**
     * Set the column/field name to search.
     * Supports dot notation for nested fields (e.g., 'Address.City').
     * Accepts both camelCase (DTO style) and PascalCase (API style).
     */
    public function column(string $column): self
    {
        $this->column = $this->normalizeFieldName($column);

        return $this;
    }

    /**
     * Normalize a field name to Simpro API format (PascalCase).
     * Accepts both camelCase (DTO style) and PascalCase (API style).
     *
     * Examples:
     *   'name' -> 'Name'
     *   'Name' -> 'Name'
     *   'id' -> 'ID'
     *   'address.line1' -> 'Address.Line1'
     *   'Address.Line2' -> 'Address.Line2'
     */
    private function normalizeFieldName(string $column): string
    {
        // Special cases - common abbreviations that should be uppercase
        $specialCases = [
            'id' => 'ID',
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

        // Split on dots for nested fields
        $parts = explode('.', $column);
        $normalized = [];

        foreach ($parts as $part) {
            $lowerPart = strtolower($part);
            if (isset($specialCases[$lowerPart])) {
                $normalized[] = $specialCases[$lowerPart];
            } else {
                // Convert to PascalCase (capitalize first letter)
                $normalized[] = ucfirst($part);
            }
        }

        return implode('.', $normalized);
    }

    /**
     * Exact match (column=value).
     */
    public function equals(string|int|float|bool $value): self
    {
        $this->value = $this->formatValue($value);

        return $this;
    }

    /**
     * Wildcard search (%value%).
     * URL encodes the % symbols for API compatibility.
     */
    public function find(string $value): self
    {
        $this->value = '%25'.$value.'%25';

        return $this;
    }

    /**
     * Like/contains search (alias for find).
     */
    public function like(string $value): self
    {
        return $this->find($value);
    }

    /**
     * Starts with search (value%).
     */
    public function startsWith(string $value): self
    {
        $this->value = $value.'%25';

        return $this;
    }

    /**
     * Ends with search (%value).
     */
    public function endsWith(string $value): self
    {
        $this->value = '%25'.$value;

        return $this;
    }

    /**
     * Less than comparison (<value).
     */
    public function lessThan(string|int|float $value): self
    {
        $this->value = '<'.$this->formatValue($value);

        return $this;
    }

    /**
     * Less than or equal comparison (<=value).
     */
    public function lessThanOrEqual(string|int|float $value): self
    {
        $this->value = '<='.$this->formatValue($value);

        return $this;
    }

    /**
     * Greater than comparison (>value).
     */
    public function greaterThan(string|int|float $value): self
    {
        $this->value = '>'.$this->formatValue($value);

        return $this;
    }

    /**
     * Greater than or equal comparison (>=value).
     */
    public function greaterThanOrEqual(string|int|float $value): self
    {
        $this->value = '>='.$this->formatValue($value);

        return $this;
    }

    /**
     * Not equal comparison (!=value).
     */
    public function notEqual(string|int|float|bool|null $value): self
    {
        if ($value === null) {
            $this->value = '!=null';
        } else {
            $this->value = '!='.$this->formatValue($value);
        }

        return $this;
    }

    /**
     * Between range comparison (min~max).
     */
    public function between(string|int|float $min, string|int|float $max): self
    {
        $this->value = $this->formatValue($min).'~'.$this->formatValue($max);

        return $this;
    }

    /**
     * Value is in a list (value1,value2,value3).
     *
     * @param  array<string|int|float>  $values
     */
    public function in(array $values): self
    {
        $formatted = array_map(fn ($v) => $this->formatValue($v), $values);
        $this->value = implode(',', $formatted);

        return $this;
    }

    /**
     * Value is not in a list (!=value1,!=value2).
     *
     * @param  array<string|int|float>  $values
     */
    public function notIn(array $values): self
    {
        $formatted = array_map(fn ($v) => '!='.$this->formatValue($v), $values);
        $this->value = implode(',', $formatted);

        return $this;
    }

    /**
     * Get the column name.
     */
    public function getColumn(): ?string
    {
        return $this->column;
    }

    /**
     * Get the formatted value.
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Convert to query parameter array [column => value].
     *
     * @return array{0: string, 1: string}
     *
     * @throws InvalidArgumentException if column or value is not set
     */
    public function toQueryParam(): array
    {
        if ($this->column === null) {
            throw new InvalidArgumentException('Search column must be set');
        }

        if ($this->value === null) {
            throw new InvalidArgumentException('Search value must be set');
        }

        return [$this->column, $this->value];
    }

    /**
     * Format a value for use in query parameters.
     */
    private function formatValue(string|int|float|bool $value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}
