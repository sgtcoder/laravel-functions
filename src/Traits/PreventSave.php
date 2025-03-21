<?php

namespace SgtCoder\LaravelFunctions\Traits;

trait PreventSave
{
    protected $preventSave = false;

    public function setPreventSave($value = true)
    {
        $this->preventSave = $value;
        return $this;
    }

    public function save(array $options = [])
    {
        if ($this->preventSave) {
            return false;
        }
        return parent::save($options);
    }
}
