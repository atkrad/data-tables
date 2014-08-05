<?php

namespace DataTable\Request;

use DataTable\AccessorTrait;

/**
 * Class Order
 *
 * @method Order setColumn($column) Set order column
 * @method Order setDir($dir) Set order direction
 * @method getColumn() Get order column
 * @method getDir() Get order direction
 *
 * @package DataTable\Request
 */
class Order
{
    use AccessorTrait;

    protected $column;
    protected $dir;

    const DIR_ASC = 'asc';
    const DIR_DESC = 'desc';

    public function __construct($order)
    {
        $this->setColumn($order['column'])
            ->setDir($order['dir']);
    }
}
