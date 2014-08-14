<?php

namespace DataTable\Test\TestCase;

use DataTable\Extension\ColVis;
use PHPUnit_Framework_TestCase;

/**
 * ArrayAccessTrait Test
 *
 * @package DataTable\Test\TestCase
 */
class ArrayAccessTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Offset exist test
     */
    public function testOffsetExist()
    {
        $colVis = new ColVis();
        $this->assertEquals($colVis->offsetExists('all'), false);
    }

    /**
     * Offset get test
     */
    public function testOffsetGet()
    {
        $colVis = new ColVis();
        $this->assertEquals($colVis['all'], []);
    }

    /**
     * Offset unset and set test
     */
    public function testOffsetUnsetSet()
    {
        $colVis = new ColVis();
        $colVis['foo'] = 'bar';
        $this->assertEquals($colVis['foo'], 'bar');

        unset($colVis['foo']);
        $this->assertEquals($colVis['foo'], []);
    }
}
