<?php

namespace DataTable\Request;

use DataTable\AccessorTrait;

/**
 * Class Search
 *
 * @method Search setValue($value) Set search value.
 * @method getValue() Get search value.
 * @method getRegex() Get search regex.
 *
 * @package DataTable\Request
 */
class Search
{
    use AccessorTrait;

    protected $value;
    protected $regex;

    public function __construct(array $search)
    {
        $this->setValue($search['value'])
            ->setRegex($search['regex']);
    }

    public function setRegex($regex)
    {
        if ($regex === 'false') {
            $this->regex = false;

            return $this;
        }
        $this->regex = true;

        return $this;
    }
}
