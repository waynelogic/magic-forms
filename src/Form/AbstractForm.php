<?php

namespace Waynelogic\MagicForms\Form;

use Illuminate\Contracts\Validation\ValidationRule;

abstract class AbstractForm
{
    public string $group = 'Common Form';

    public bool $onlyValidated = false;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    abstract public function rules(): array;

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    abstract public function attributes(): array;

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function pass() : array
    {
        return [];
    }

    /**
     * Define notification routes for anonymous notifiable.
     *
     * Format: ['channel' => 'route']
     * Example: ['mail' => 'user@example.com', 'telegram' => '123456789']
     *
     * @return array<string, string|int|mixed>
     */
    public function routes(): array
    {
        return [];
    }
}
