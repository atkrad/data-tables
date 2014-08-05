<?php

namespace DataTable\DataSource;

use DataTable\Table;

/**
 * Dom Data Source
 *
 * The foundation for DataTables is progressive enhancement, so it is
 * very adept at reading table information directly from the DOM. This
 * example shows how easy it is to add searching, ordering and paging
 * to your HTML table by simply running DataTables on it.
 *
 * @package DataTable\DataSource
 */
class Dom implements DataSourceInterface
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var string
     */
    protected $tableId;

    /**
     * Dom Data Source Constructor
     *
     * @param string $tableId Table id
     */
    function __construct($tableId)
    {
        $this->tableId = $tableId;
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
        $this->table->setTableId($this->tableId);
    }
}
