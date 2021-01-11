<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CartItems implements Rule
{

    /**
     * @var Validator
     */
    private  $validator;

    public function rules()
    {
        return [
            'packageId' => ['required', new PackageId],
            'additionals.*' => ['required', new Additionals()],
            'additionals' => ['required'],
        ];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $this->validator = Validator::make($value, $this->rules());

        return !$this->validator->fails();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->validator->errors();
    }
}
