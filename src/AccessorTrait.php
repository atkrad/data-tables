<?php

namespace DataTable;

use BadMethodCallException;

trait AccessorTrait
{
    function __call($name, $arguments)
    {
        if ($this->isSetMethod($name)) {
            $this->setPropertyValue($name, $arguments);

            return $this;
        } elseif ($this->isGetMethod($name)) {
            return $this->getPropertyValue($name);
        }

        throw new BadMethodCallException(sprintf('Method "%s" not exist.', $name));
    }

    protected function setPropertyValue($methodName, $arguments)
    {
        $property = lcfirst(substr($methodName, 3));

        if (property_exists($this, $property)) {
            $this->{$property} = $arguments[0];
        } else {
            throw new BadMethodCallException(sprintf('Method "%s" not exist.', $methodName));
        }
    }

    protected function getPropertyValue($methodName)
    {
        $property = $this->getPropertyName($methodName);

        if (property_exists($this, $property)) {
            return $this->{$property};
        } else {
            throw new BadMethodCallException(sprintf('Method "%s" not exist.', $methodName));
        }
    }

    protected function getPropertyName($methodName)
    {
        return lcfirst(substr($methodName, 3));
    }

    protected function isSetMethod($methodName)
    {
        if (strpos($methodName, 'set', 0) !== false) {
            return true;
        }

        return false;
    }

    protected function isGetMethod($methodName)
    {
        if (strpos($methodName, 'get', 0) !== false) {
            return true;
        }

        return false;
    }
}
