<?php

namespace DataTable\DataSource;

use DataTable\Table;

/**
 * DataSource Interface
 *
 * @package DataTable\DataSource
 */
interface DataSourceInterface
{
    /**
     * Initialize data source
     *
     * @param Table $table Table object
     *
     * @return void
     */
    public function initialize(Table $table);
}
