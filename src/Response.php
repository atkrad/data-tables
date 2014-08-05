<?php

namespace DataTable;

/**
 * Class Response
 *
 * @method Response setDraw($draw) Set response draw.
 * @method Response setRecordsTotal($recordsTotal) Set records total.
 * @method Response setRecordsFiltered($recordsFiltered) Set records filtered.
 * @method Response setData($data) Set data.
 * @method getDraw() Get response draw.
 * @method getRecordsTotal() Get records total.
 * @method getRecordsFiltered() Get records filtered.
 * @method getData() Get data.
 *
 * @package DataTable
 */
class Response
{
    use AccessorTrait;

    protected $draw;
    protected $recordsTotal;
    protected $recordsFiltered;
    protected $data;

    public function toArray()
    {
        return [
            'draw' => $this->getDraw(),
            'recordsTotal' => $this->getRecordsTotal(),
            'recordsFiltered' => $this->getRecordsFiltered(),
            'data' => $this->getData()
        ];
    }

    public function toString()
    {
        return json_encode($this->toArray());
    }

    public function __toString()
    {
        return $this->toString();
    }
}
