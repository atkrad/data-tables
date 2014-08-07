<?php

namespace DataTable\Column;

use DataTable\Table;

interface ColumnInterface
{
    /**
     * Initialize table
     *
     * @param Table $table Table object
     *
     * @return void
     */
    public function initialize(Table $table);

    /**
     * Get column content
     *
     * @param mixed $rowResult Per row result
     *
     * @return string
     */
    public function getContent($rowResult);
}
