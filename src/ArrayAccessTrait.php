<?php

namespace DataTable;

/**
 * ArrayAccess Trait
 *
 * @package DataTable
 */
trait ArrayAccessTrait
{
    /**
     * Whether a offset exists
     *
     * @param mixed $offset An offset to check for.
     *
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->properties);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     */
    public function offsetGet($offset)
    {
        if (array_key_exists($offset, $this->properties)) {
            return $this->properties[$offset];
        } else {
            return $this->properties;
        }
    }

    /**
     * Offset to set
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @return void
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     */
    public function offsetSet($offset, $value)
    {
        $this->properties[$offset] = $value;
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset The offset to unset.
     *
     * @return void
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     */
    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, $this->properties)) {
            unset($this->properties[$offset]);
        }
    }
}
