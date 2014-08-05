<?php

namespace DataTable;

use DataTable\DataSource\DataSourceInterface;
use DataTable\DataSource\ServerSide\ServerSideInterface;
use DataTable\DataSource\SimpleDataSourceInterface;
use DataTable\Extension\ExtensionInterface;

/**
 * Class Table
 *
 * @method Table setTableId() Set table id.
 * @method getTableId() Get table id.
 * @method DataSourceInterface|ServerSideInterface|SimpleDataSourceInterface getDataSource() Get table data source.
 * @method getProperties() Get table properties.
 * @method getCallbacks() Get table callbacks.
 * @method Column[] getColumns() Get table columns.
 * @method ExtensionInterface[] getExtensions() Get table extensions.
 *
 * @package DataTable
 */
class Table
{
    use AccessorTrait;

    /**
     * Table columns
     *
     * @var Column[]
     */
    protected $columns = [];

    /**
     * Table properties
     *
     * @var array
     */
    protected $properties = [];

    /**
     * Table callbacks
     *
     * @var array
     */
    protected $callbacks = [];

    /**
     * Table id
     *
     * @var string
     */
    protected $tableId;

    /**
     * DataTable data source
     *
     * @var DataSourceInterface
     */
    protected $dataSource;
    protected $debug = false;

    /**
     * DataTable extensions
     *
     * @var ExtensionInterface[]
     */
    protected $extensions = [];

    /**
     * Table constructor
     */
    public function __construct()
    {
        $this->setTableId(md5(time()));
    }

    /**
     * Set Table data source
     *
     * @param DataSourceInterface $dataSource
     */
    public function setDataSource(DataSourceInterface $dataSource)
    {
        $this->dataSource = $dataSource;
        $this->dataSource->initialize($this);
    }

    public function setProperty($property, $value)
    {
        $this->properties[$property] = $value;

        return $this;
    }

    public function getProperty($property)
    {
        return $this->properties[$property];
    }

    public function isDebugMode($debug)
    {
        $this->debug = $debug;
    }

    public function getDebugMode()
    {
        return $this->debug;
    }

    /**
     * Add column to table
     *
     * @param \DataTable\Column $column Column object
     *
     * @return Table
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * Add extension to table
     *
     * @param ExtensionInterface $extension DataTable extension
     *
     * @return Table
     */
    public function addExtension(ExtensionInterface $extension)
    {
        $this->extensions[] = $extension;

        return $this;
    }

    /**
     * Get DataTable response
     *
     * @return Response
     * @throws Exception
     */
    public function getResponse()
    {
        if (!$this->dataSource instanceof ServerSideInterface) {
            throw new Exception('You must call this method when use server side data source.');
        }

        $response = new Response();
        $request = new Request();

        $this->dataSource->getResponse($response, $request);

        return $response;
    }

    public function render()
    {
        $render = new Render($this);

        return $render->render();
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Enable or disable automatic column width calculation. This can be disabled as an optimisation (it takes
     * a finite amount of time to calculate the widths) if the tables widths are passed in using
     * columns.widthDT.
     *
     * @param bool $autoWidth Feature control DataTables' smart column width handling.
     *
     * @return Table
     * @see http://datatables.net/reference/option/autoWidth
     */
    public function isAutoWidth($autoWidth)
    {
        $this->properties['autoWidth'] = $autoWidth;

        return $this;
    }

    /**
     * By default, when DataTables loads data from an Ajax or Javascript data source (ajaxDT and
     * dataDT respectively) it will create all HTML elements needed up-front. When working with large data
     * sets, this operation can take a not-insignificant amount of time, particularly in older browsers such as
     * IE6-8. This option allows DataTables to create the nodes (rows and cells in the table body) only when
     * they are needed for a draw.
     *
     * @param bool $deferRender Feature control deferred rendering for additional speed of initialisation.
     *
     * @return Table
     * @see http://datatables.net/reference/option/deferRender
     */
    public function isDeferRender($deferRender)
    {
        $this->properties['deferRender'] = $deferRender;

        return $this;
    }

    /**
     * Disable will show information about the table including information about filtered data if that action is
     * being performed. This option allows that feature to be enabled or disabled.
     *
     * @param bool $info Feature control table information display field.
     *
     * @return Table
     * @see http://datatables.net/reference/option/info
     */
    public function isInfo($info)
    {
        $this->properties['info'] = $info;

        return $this;
    }

    /**
     * DataTables can be styled by a number of CSS library packages, included jQuery UI, Twitter Bootstrap
     * and Foundation. Although Bootstrap, Foundation and other libraries require a plug-in, jQuery UI
     * ThemeRoller support is built into DataTables and can be enabled simply with this parameter. When
     * enabled, DataTables will use markup and classes created for jQuery UI ThemeRoller, making it very
     * easy to integrate your tables with jQuery UI.
     *
     * @param bool $jQueryUI Use markup and classes for the table to be themed by jQuery UI ThemeRoller.
     *
     * @return Table
     * @see        http://datatables.net/reference/option/jQueryUI
     * @deprecated Note that this feature is deprecated in DataTables 1.10 and will be removed in 1.11, where upon this
     * feature will be provided by plug-ins, matching the other style libraries.
     */
    public function isJQueryUI($jQueryUI)
    {
        $this->properties['jQueryUI'] = $jQueryUI;

        return $this;
    }

    /**
     * When pagination is enabled, this option will display an option for the end user to change number of
     * records to be shown per page. The options shown in the list are controlled by the lengthMenuDT
     * configuration option.
     *
     * @param bool $lengthChange Feature control the end user's
     *                           ability to change the paging display length of the table.
     *
     * @return Table
     * @see http://datatables.net/reference/option/lengthChange
     */
    public function isLengthChange($lengthChange)
    {
        $this->properties['lengthChange'] = $lengthChange;

        return $this;
    }

    /**
     * Enable or disable ordering of columns - it is as simple as that! DataTables, by default, allows end users
     * to click on the header cell for each column, ordering the table by the data in that column. The ability to
     * order data can be disabled using this option.
     *
     * @param bool $ordering Feature control ordering (sorting) abilities in DataTables.
     *
     * @return Table
     * @see http://datatables.net/reference/option/ordering
     */
    public function isOrdering($ordering)
    {
        $this->properties['ordering'] = $ordering;

        return $this;
    }

    /**
     * DataTables can split the rows in tables into individual pages, which is an efficient method of showing a
     * large number of records in a small space. The end user is provided with controls to request the display
     * of different data as the navigate through the data. This feature is enabled by default, but if you wish to
     * disable it, you may do so with this parameter.
     *
     * @param bool $paging Enable or disable table pagination.
     *
     * @return Table
     * @see http://datatables.net/reference/option/paging
     */
    public function isPaging($paging)
    {
        $this->properties['paging'] = $paging;

        return $this;
    }

    /**
     * Enable or disable the display of a 'processing' indicator when the table is being processed (e.g. a sort).
     * This is particularly useful for tables with large amounts of data where it can take a noticeable amount of
     * time to sort the entries.
     *
     * @param bool $processing Feature control the processing indicator.
     *
     * @return Table
     * @see http://datatables.net/reference/option/processing
     */
    public function isProcessing($processing)
    {
        $this->properties['processing'] = $processing;

        return $this;
    }

    /**
     * Enable horizontal scrolling. When a table is too wide to fit into a certain layout, or you have a large
     * number of columns in the table, you can enable horizontal (x) scrolling to show the table in a viewport,
     * which can be scrolled.
     *
     * @param bool $scrollX Horizontal scrolling.
     *
     * @return Table
     * @see http://datatables.net/reference/option/scrollX
     */
    public function isScrollX($scrollX)
    {
        $this->properties['scrollX'] = $scrollX;

        return $this;
    }

    /**
     * Enable vertical scrolling. Vertical scrolling will constrain the DataTable to the given height, and enable
     * scrolling for any data which overflows the current viewport. This can be used as an alternative to paging
     * to display a lot of data in a small area (although paging and scrolling can both be enabled at the same
     * time if desired). This property can be any CSS unit, or a number (in which case it will be treated as a
     * pixel measurement).
     *
     * @param string $scrollY Vertical scrolling.
     *
     * @return Table
     * @see http://datatables.net/reference/option/scrollY
     */
    public function setScrollY($scrollY)
    {
        $this->properties['scrollY'] = $scrollY;

        return $this;
    }

    /**
     * This option allows the search abilities of DataTables to be enabled or disabled. Searching in
     * DataTables is "smart" in that it allows the end user to input multiple words (space separated) and will
     * match a row containing those words, even if not in the order that was specified (this allow matching
     * across multiple columns).
     *
     * @param bool $searching Feature control search (filtering) abilities.
     *
     * @return Table
     * @see   http://datatables.net/reference/option/searching
     */
    public function isSearching($searching)
    {
        $this->properties['searching'] = $searching;

        return $this;
    }

    /**
     * DataTables has two fundamental modes of operation:
     *
     * Client-side processing - where filtering, paging and sorting calculations are all performed in the web-browser.
     * Server-side processing - where filtering, paging and sorting calculations are all performed by a server.
     *
     * @param bool $serverSide Feature control DataTables' server-side processing mode.
     *
     * @return Table
     * @see http://datatables.net/reference/option/serverSide
     */
    public function isServerSide($serverSide)
    {
        $this->properties['serverSide'] = $serverSide;

        return $this;
    }

    /**
     * Enable or disable state saving. When enabled aDataTables will storage state information such as
     * pagination position, display length, filtering and sorting. When the end user reloads the page the table's
     * state will be altered to match what they had previously set up.
     *
     * @param bool $stateSave State saving - restore table state on page reload.
     *
     * @return Table
     * @see http://datatables.net/reference/option/stateSave
     */
    public function isStateSave($stateSave)
    {
        $this->properties['stateSave'] = $stateSave;

        return $this;
    }

    /**
     * The columnsDT option in the initialisation parameter allows you to define details about the way
     * individual columns behave. For a full list of column options that can be set.
     *
     * @param array $columns Set column specific initialisation properties.
     *
     * @return Table
     * @see http://datatables.net/reference/option/columns
     */
    public function setColumns($columns)
    {
        $this->properties['columns'] = $columns;

        return $this;
    }

    /**
     * DataTables can obtain the data it is to display in the table table's body from a number of sources,
     * including from an Ajax data source, using this initialisation parameter. As with other dynamic data
     * sources, arrays or objects can be used for the data source for each row, with columns.dataDT
     * employed to read from specific object properties.
     *
     * @param string|object|callback $ajax Load data for the table's content from an Ajax source.
     *
     * @return Table
     * @see http://datatables.net/reference/option/ajax
     */
    public function setAjax($ajax)
    {
        //@todo when ajax is object and have "data" property and data value is function this method not handled.
        if (is_string($ajax)) {
            $pattern = '/^(\s+)*(function)(\s+)*\(/i';
            if (preg_match($pattern, $ajax, $matches) && strtolower($matches[2]) == 'function') {
                $hash = sha1($ajax);
                $this->properties['ajax'] = $hash;
                $this->callbacks[$hash] = $ajax;
                return $this;
            }

            $this->properties['ajax'] = $ajax;
            return $this;
        } else {
            $this->properties['ajax'] = $ajax;
            return $this;
        }
    }

    /**
     * DataTables can obtain the data it is to display in the table table's body from a number of sources,
     * including being passed in as an array of row data using this initialisation parameter. As with other
     * dynamic data sources, arrays or objects can be used for the data source for each row, with
     * columns.dataDT employed to read from specific object properties.
     *
     * @param array $data Data to use as the display data for the table.
     *
     * @return Table
     * @see http://datatables.net/reference/option/data
     */
    public function setData(array $data)
    {
        $this->properties['data'] = $data;

        return $this;
    }
}
