<?php

namespace App\Http\Validation\Match;

use App\Http\Validation\ValidationInterface;
use Validator;

abstract class Base implements ValidationInterface
{
    /**
     * @var array
     */
    private $errorMessages = [];

    abstract public function rules();

    abstract public function getAllowedFields();

    /**
     * @param array $data
     */
    public function validate(array $data)
    {
        $validator = Validator::make($data, $this->rules());
        if ($validator->fails()) {
            $this->errorMessages = $validator->messages()->all();
        }

        $this->checkAllowedFields($data);
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        if (empty($this->errorMessages)) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @param array $data
     */
    private function checkAllowedFields(array $data)
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->getAllowedFields())) {
                $this->errorMessages[] = [
                    "Not allowed field '$key'. Allowed fields list: " => $this->getAllowedFields()
                ];

                return;
            }
        }
    }
}
