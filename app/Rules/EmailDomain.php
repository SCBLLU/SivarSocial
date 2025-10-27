<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    // Para validar el dominio itca.edu.sv

    protected $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!str_ends_with(strtolower($value), '@' . strtolower($this->domain))) {
            $fail("El correo debe pertenecer al dominio @{$this->domain}.");
        }
    }
}
