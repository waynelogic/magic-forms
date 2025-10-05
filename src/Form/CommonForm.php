<?php

namespace Waynelogic\MagicForms\Form;

use Illuminate\Contracts\Validation\ValidationRule;

class CommonForm extends AbstractForm
{
    public string $group = 'Common Form';

    public bool $onlyValidated = false;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }
}
