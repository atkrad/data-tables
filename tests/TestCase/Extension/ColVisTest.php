<?php

namespace DataTable\Test\TestCase\Extension;

use DataTable\Extension\ColVis;
use DataTable\Table;
use PHPUnit_Framework_TestCase;

/**
 * ColVis Test
 *
 * @package DataTable\Test\TestCase\Extension
 */
class ColVisTest extends PHPUnit_Framework_TestCase
{
    /**
     * Extension interface test
     */
    public function testExtensionInterface()
    {
        $table = new Table();
        $table->addExtension(new ColVis());

        $this->assertEquals($table->getExtension(ColVis::PROPERTY_NAME)->getPropertyName(), ColVis::PROPERTY_NAME);
        $this->assertEquals($table->getExtension(ColVis::PROPERTY_NAME)->getDomName(), ColVis::DOM_NAME);
        $this->assertEquals($table->getExtension(ColVis::PROPERTY_NAME)->getProperties(), []);
        $this->assertEquals($table->getExtension(ColVis::PROPERTY_NAME)->getCallbacks(), []);
    }
}
