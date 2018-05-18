<?php

namespace CartLoad\Cart;


trait Errors
{
    /**
     * @var string[]
     */
    protected $errors = [];

    /**
     * Add multiple errors
     *
     * @param string[] $errors
     */
    public function addErrors(array $errors)
    {
        foreach ($errors as $key => $error) {
            $this->addError($error, $key);
        }
    }

    /**
     * Add an error
     *
     * @param $error
     * @param bool $key
     *
     * @return $this
     */
    public function addError($error, $key = false)
    {
        if ($key) {
            $this->errors [$key] = $error;
        } else {
            $this->errors [] = $error;
        }

        return $this;
    }

    /**
     * Get all errors
     * @return \string[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Check to see if there are any errors
     * @return \bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * Check to see if there is error by the key
     *
     * @param $key
     *
     * @return \bool
     */
    public function hasError($key)
    {
        return isset($this->errors[$key]);
    }

    /**
     * Clear all errors
     * @return $this
     */
    public function clearErrors()
    {
        $this->errors = [];

        return $this;
    }

    /**
     * Get an error by its key
     *
     * @param $key
     *
     * @return bool|string
     */
    public function getErrorByKey($key)
    {
        if (isset($this->errors[$key])) {
            return $this->errors[$key];
        } else {
            return false;
        }
    }
}