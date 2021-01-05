<?php


namespace App\Rules;


use App\Models\Package;

class PackageId implements \Illuminate\Contracts\Validation\Rule
{

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value): bool
    {
        return Package::where('id', $value)->exists() ;
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return "Package doesn't exist";
    }
}
