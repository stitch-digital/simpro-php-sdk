<?php

declare(strict_types=1);

namespace Simpro\PhpSdk\Simpro\Exceptions;

use Saloon\Exceptions\Request\Statuses\UnprocessableEntityException;

final class ValidationException extends UnprocessableEntityException
{
    /**
     * @var array<string, array<int, string>>
     */
    private array $errors = [];

    public function __construct(\Saloon\Http\Response $response)
    {
        parent::__construct($response);

        $data = $response->json();

        if (isset($data['errors']) && is_array($data['errors'])) {
            $this->errors = $data['errors'];
        }
    }

    /**
     * Get all validation errors.
     *
     * @return array<string, array<int, string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get errors for a specific field.
     *
     * @return array<int, string>
     */
    public function getErrorsForField(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * Check if there are errors for a specific field.
     */
    public function hasErrorsForField(string $field): bool
    {
        return isset($this->errors[$field]) && count($this->errors[$field]) > 0;
    }

    /**
     * Get all error messages as a flat array.
     *
     * @return array<int, string>
     */
    public function getAllErrorMessages(): array
    {
        $messages = [];

        foreach ($this->errors as $fieldErrors) {
            $messages = array_merge($messages, $fieldErrors);
        }

        return $messages;
    }

    /**
     * Build a human-readable error message.
     */
    public function buildMessage(): string
    {
        if (empty($this->errors)) {
            return $this->getMessage();
        }

        $messages = [];

        foreach ($this->errors as $field => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $messages[] = sprintf('%s: %s', $field, $error);
            }
        }

        return implode('; ', $messages);
    }
}
