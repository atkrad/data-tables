<?php

namespace DataTable;

use DataTable\Request\Column;
use DataTable\Request\Order;
use DataTable\Request\Search;
use DateTime;

/**
 * Class Request
 *
 * @method Request setDraw($draw) Set request draw.
 * @method Request setStart($start) Set page start.
 * @method Request setLength($length) Set page length.
 * @method getDraw() Get request draw.
 * @method Column[] getColumns() Get request columns.
 * @method Order[] getOrder() Get request order.
 * @method getStart() Get page start.
 * @method getLength() Get page length.
 * @method Search getSearch() Get search.
 *
 * @package DataTable
 */
class Request
{
    use AccessorTrait;

    protected $draw;
    /**
     * @var Column[]
     */
    protected $columns;
    /**
     * @var Order[]
     */
    protected $order;
    protected $start;
    protected $length;
    /**
     * @var Search
     */
    protected $search;
    /**
     * @var DateTime
     */
    protected $time;

    public function __construct()
    {
        parse_str($_SERVER['QUERY_STRING'], $query);

        if (array_key_exists('draw', $query) && array_key_exists('columns', $query) && array_key_exists('_', $query)) {
            $this->setDraw($query['draw'])
                ->setColumns($query['columns'])
                ->setOrder($query['order'])
                ->setStart($query['start'])
                ->setLength($query['length'])
                ->setSearch($query['search'])
                ->setTime($query['_']);
        }
    }

    /**
     * @param array $columns
     *
     * @return Request
     */
    public function setColumns(array $columns)
    {
        foreach ($columns as $column) {
            $this->columns[] = new Column($column);
        }

        return $this;
    }

    /**
     * @param array $orders
     *
     * @return Request
     */
    public function setOrder(array $orders)
    {
        foreach ($orders as $order) {
            $this->order[] = new Order($order);
        }

        return $this;
    }

    /**
     * @param array $search
     *
     * @return Request
     */
    public function setSearch(array $search)
    {
        $this->search = new Search($search);

        return $this;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = new DateTime();
        $this->time->setTimestamp($time);
    }
}
