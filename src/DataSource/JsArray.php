<?php

namespace DataTable\DataSource;

use DataTable\Column\ColumnInterface;
use DataTable\Table;

/**
 * JsArray Data Source
 *
 * At times you will wish to be able to create a table from dynamic information passed directly
 * to DataTables, rather than having it read from the document. This is achieved using the dataDT
 * option in the initialisation object, passing in an array of data to be used (like all other
 * DataTables handled data, this can be arrays or objects using the columns.dataDT option).
 *
 * @package DataTable\DataSource
 */
class JsArray implements DataSourceInterface
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * JsArray data source constructor
     *
     * @param array $data Data to use as the display data for the table.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Initialize data source
     *
     * @param Table $table Table object
     *
     * @return void
     */
    public function initialize(Table $table)
    {
        $this->table = $table;
        $this->table->setData($this->getData());
    }

    /**
     * Get data
     *
     * @return array
     */
    protected function getData()
    {
        $data = [];
        foreach ($this->data as $dataValue) {
            $row = [];
            foreach ($this->table->getColumns() as $column) {
                if ($column instanceof ColumnInterface) {
                    $row[$column->getData()] = $column->getContent($dataValue);
                    continue;
                }

                if (is_callable($column->getFormatter())) {
                    $row[$column->getData()] = call_user_func_array(
                        $column->getFormatter(),
                        [
                            $dataValue[$column->getData()],
                            $dataValue
                        ]
                    );
                } else {
                    $row[$column->getData()] = (string)$dataValue[$column->getData()];
                }
            }
            $data[] = $row;
        }

        return $data;
    }
}
