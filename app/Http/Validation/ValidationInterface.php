<?php

namespace App\Http\Validation;

interface ValidationInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function validate(array $data);
}
