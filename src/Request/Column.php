<?php

namespace DataTable\Request;

use DataTable\AccessorTrait;

/**
 * Class Column
 *
 * @method Column setData($data) Set column data.
 * @method Column setName($name) Set column name.
 * @method getData() Get column data.
 * @method getSearchable() Is column searchable.
 * @method getOrderable() Is column orderable.
 * @method Search getSearch() Get column search.
 *
 * @package DataTable\Request
 */
class Column
{
    use AccessorTrait;

    protected $data;
    protected $name;
    protected $searchable;
    protected $orderable;
    protected $search;

    public function __construct($column)
    {
        $this->setData($column['data'])
            ->setName($column['name'])
            ->setSearchable($column['searchable'])
            ->setOrderable($column['orderable'])
            ->setSearch($column['search']);
    }

    /**
     * @param string $searchable
     *
     * @return Column
     */
    public function setSearchable($searchable)
    {
        if ($searchable === 'true') {
            $this->searchable = true;

            return $this;
        }
        $this->searchable = false;

        return $this;
    }

    /**
     * @param string $orderable
     *
     * @return Column
     */
    public function setOrderable($orderable)
    {
        if ($orderable === 'true') {
            $this->orderable = true;

            return $this;
        }
        $this->orderable = false;

        return $this;
    }

    /**
     * @param array $search
     */
    public function setSearch(array $search)
    {
        $this->search = new Search($search);
    }
}
