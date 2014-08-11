<?php

namespace DataTable\Test\TestCase;

use DataTable\Table;
use PHPUnit_Framework_TestCase;

/**
 * Table Test
 *
 * @package TestCase
 */
class TableTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set and get table property test
     */
    public function testSetGetTableProperty()
    {
        $table = new Table();

        $table->isAutoWidth(true);
        $this->assertEquals($table->getProperty('autoWidth'), true);

        $table->isDeferRender(false);
        $this->assertEquals($table->getProperty('deferRender'), false);

        $table->isInfo(true);
        $this->assertEquals($table->getProperty('info'), true);

        $table->isJQueryUI(false);
        $this->assertEquals($table->getProperty('jQueryUI'), false);

        $table->isLengthChange(true);
        $this->assertEquals($table->getProperty('lengthChange'), true);

        $table->isOrdering(false);
        $this->assertEquals($table->getProperty('ordering'), false);

        $table->isPaging(true);
        $this->assertEquals($table->getProperty('paging'), true);

        $table->isProcessing(false);
        $this->assertEquals($table->getProperty('processing'), false);

        $table->isScrollX(true);
        $this->assertEquals($table->getProperty('scrollX'), true);

        $table->setScrollY('150px');
        $this->assertEquals($table->getProperty('scrollY'), '150px');

        $table->isSearching(false);
        $this->assertEquals($table->getProperty('searching'), false);

        $table->isServerSide(true);
        $this->assertEquals($table->getProperty('serverSide'), true);

        $table->isStateSave(false);
        $this->assertEquals($table->getProperty('stateSave'), false);

        $table->setAjax('http://example.org/my/ajax/path');
        $this->assertEquals($table->getProperty('ajax'), 'http://example.org/my/ajax/path');

        $ajaxCallback = "function (data, callback, settings) {
                callback(
                  JSON.parse( localStorage.getItem('dataTablesData') )
                );
              }";
        $ajaxHash = sha1($ajaxCallback);
        $table->setAjax($ajaxCallback);
        $this->assertEquals($table->getProperty('ajax'), $ajaxHash);
        $this->assertContains($ajaxCallback, $table->getCallbacks()[$ajaxHash]);

        $table->setAjax(['url' => 'data.json', 'type' => 'POST']);
        $this->assertArrayHasKey('url', $table->getProperty('ajax'));

        $data = [
            [
                "name" => "Tiger Nixon",
                "position" => "System Architect",
                "salary" => "$3,120",
                "start_date" => "2011/04/25",
                "office" => "Edinburgh",
                "extn" => 5421
            ],
            [
                "name" => "Garrett Winters",
                "position" => "Director",
                "salary" => "5300",
                "start_date" => "2011/07/25",
                "office" => "Edinburgh",
                "extn" => "8422"
            ]
        ];

        $table->setData($data);
        $this->assertEquals($table->getProperty('data'), $data);
    }

    /**
     * Render test
     */
    public function testRenderAndToString()
    {
        $table = new Table();

        $needleTableTag = sprintf('<table id="%s" class="dataTable display">', $table->getTableId());
        $needleTableJsSelector = sprintf("$('#%s').dataTable({", $table->getTableId());

        $this->assertContains($needleTableTag, $table->render());
        $this->assertContains($needleTableJsSelector, $table->render());
        $this->assertContains($needleTableJsSelector, (string)$table);
    }
}
