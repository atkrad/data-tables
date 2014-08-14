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

    public function testSetterGetterColVisProperty()
    {
        $colVis = new ColVis();

        $colVis->setActivate(ColVis::ACTIVATE_MOUSE_OVER);
        $this->assertEquals($colVis['activate'], ColVis::ACTIVATE_MOUSE_OVER);
        $colVis->setActivate(ColVis::ACTIVATE_CLICK);
        $this->assertEquals($colVis['activate'], ColVis::ACTIVATE_CLICK);

        $colVis->setExclude([0, 2]);
        $this->assertEquals($colVis['exclude'], [0, 2]);

        $colVis->setButtonText('Button Text');
        $this->assertEquals($colVis['buttonText'], 'Button Text');

        $labelFn = "function ( index, title, th ) {
            return (index+1) +'. '+ title;
          }";
        $labelHash = sha1($labelFn);

        $colVis->setLabel($labelFn);
        $this->assertEquals($colVis['label'], $labelHash);
        $this->assertEquals($colVis->getCallbacks()[$labelHash], $labelFn);

        $stateChangeFn = "function ( iColumn, bVisible ) {
            var jqTables = $('table:not(#example)'); // ColVis will do #example
            for ( var i=0, iLen=jqTables.length ; i";
        $stateChangeHash = sha1($stateChangeFn);

        $colVis->setStateChange($stateChangeFn);
        $this->assertEquals($colVis['stateChange'], $stateChangeHash);
        $this->assertEquals($colVis->getCallbacks()[$stateChangeHash], $stateChangeFn);

        $colVis->setOverlayFade(500);
        $this->assertEquals($colVis['overlayFade'], 500);

        $colVis->setAlign(ColVis::ALIGN_LEFT);
        $this->assertEquals($colVis['align'], ColVis::ALIGN_LEFT);
        $colVis->setAlign(ColVis::ALIGN_RIGHT);
        $this->assertEquals($colVis['align'], ColVis::ALIGN_RIGHT);

        $colVis->setShowAll('Show All');
        $this->assertEquals($colVis['showAll'], 'Show All');

        $colVis->setShowNone('Show None');
        $this->assertEquals($colVis['showNone'], 'Show None');

        $colVis->setRestore('Restore');
        $this->assertEquals($colVis['restore'], 'Restore');
    }
}
