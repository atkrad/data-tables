<?php

namespace DataTable\Test\TestCase;

use DataTable\Table;
use PHPUnit_Framework_TestCase;

/**
 * Render Test
 *
 * @package DataTable\Test\TestCase
 */
class RenderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Render table test
     */
    public function testRenderTable()
    {
        $table = new Table();
        $this->assertNotContains('<script>', $table->getRender()->renderTable());
    }

    /**
     * Render js test
     */
    public function testRenderJs()
    {
        $table = new Table();
        $this->assertNotContains('</table>', $table->getRender()->renderJs());
    }
}
