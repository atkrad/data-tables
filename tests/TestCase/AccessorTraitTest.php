<?php

namespace TestCase;

use BadMethodCallException;
use DataTable\Table;
use PHPUnit_Framework_TestCase;

/**
 * AccessorTrait Test
 *
 * @package TestCase
 */
class AccessorTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setter and getter test
     */
    public function testSetterGetter()
    {
        $table = new Table();
        $this->assertContains('dataTable_', $table->getTableId());

        $table->setTableId('my_table_id');
        $this->assertContains('my_table_id', $table->getTableId());
    }

    /**
     * Invalid setter prefix test
     *
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Method "invalidSetData" not exist.
     */
    public function testInvalidSetterPrefix()
    {
        $table = new Table();
        $table->invalidSetData('my_data');
    }

    /**
     * Invalid getter prefix
     *
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Method "invalidGetData" not exist.
     */
    public function testInvalidGetterPrefix()
    {
        $table = new Table();
        $table->invalidGetData();
    }

    /**
     * Setter not exist property
     *
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Method "setNotExist" not exist.
     */
    public function testSetterNotExistProperty()
    {
        $table = new Table();
        $table->setNotExist();
    }

    /**
     * Getter not exist property
     *
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Method "getNotExist" not exist.
     */
    public function testGetterNotExistProperty()
    {
        $table = new Table();
        $table->getNotExist();
    }
}
