<?php


namespace App\Rules;


use App\Models\AdditionalPackageAttribute;
use App\Models\Package;

class PackageAdditionals implements \Illuminate\Contracts\Validation\Rule
{

    /**
     * @inheritDoc
     */
    public function passes($attribute, $value): bool
    {
        return AdditionalPackageAttribute::where('id', $value)->exists();
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return "Package additional doesn't exist";
    }
}
