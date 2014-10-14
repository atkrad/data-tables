<?php

namespace DataTable;

use Closure;

/**
 * Table Column Class
 *
 * @method getFormatter() Get column formatter.
 * @method getProperties() Get column properties.
 * @method getCallbacks() Get column callbacks.
 *
 * @package DataTable
 */
class Column
{
    use AccessorTrait;

    protected $properties = [];
    protected $callbacks = [];
    protected $formatter;

    const CELL_TYPE_TH = 'th';
    const CELL_TYPE_TD = 'td';

    const TYPE_DATE = 'date';
    const TYPE_HTML = 'html';
    const TYPE_NUMERIC = 'numeric';
    const TYPE_NUMERIC_FMT = 'numeric-fmt';
    const TYPE_HTML_NUMERIC = 'html-numeric';
    const TYPE_HTML_NUMERIC_FMT = 'html-numeric-fmt';
    const TYPE_STRING = 'string';

    /**
     * Set formatter
     *
     * @param callable $formatter Column Formatter
     *
     * @return Column
     * @throws Exception
     */
    public function setFormatter(Closure $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Change the cell type created for the column - either TD cells or TH cells.
     *
     * This can be useful as TH cells have semantic meaning in the table body, allowing them to act as a
     * header for a row (you may wish to add scope='row' to the TH elements using
     * columns.createdCellDT option).
     *
     * @param string $cellType Cell type to be created for a column.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.cellType
     */
    public function setCellType($cellType)
    {
        $this->properties['cellType'] = $cellType;

        return $this;
    }

    /**
     * Quite simply this option adds a class to each cell in a column, regardless of if the table source is from
     * DOM, Javascript or Ajax. This can be useful for styling columns.
     *
     * @param string $className Class to assign to each cell in the column
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.className
     */
    public function setClassName($className)
    {
        $this->properties['className'] = $className;

        return $this;
    }

    /**
     * Add padding to the text content used when calculating the optimal with for a table.
     * The first thing to say about this property is that generally you shouldn't need this!
     *
     * @param string $contentPadding Add padding to the text content used.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.contentPadding
     */
    public function setContentPadding($contentPadding)
    {
        $this->properties['contentPadding'] = $contentPadding;

        return $this;
    }

    /**
     * This is a callback function that is executed whenever a cell is created (Ajax source, etc) or read from a
     * DOM source. It can be used as a compliment to columns.renderDT allowing modification of the cell's
     * DOM element (add background colour for example) when the element is created (cells my not be
     * immediately created on table initialisation if deferRenderDT is enabled, or if rows are dynamically
     * added using the API (rows.add()DT).
     *
     * @param callback $createdCell Cell created callback to allow DOM manipulation.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.createdCell
     */
    public function setCreatedCell($createdCell)
    {
        $hash = sha1($createdCell);
        $this->properties['createdCell'] = $hash;
        $this->callbacks[$hash] = $createdCell;

        return $this;
    }

    /**
     * Set the data source for the column from the rows data object / array
     *
     * This property can be used to read data from any data source property, including deeply nested objects
     * / properties. data can be given in a number of different ways which effect its behaviour as
     * documented below.
     *
     * @param int|string|null|js object|callback $data Set the data source for the column
     *                                                 from the rows data object / array
     *
     * @return Column
     * @see   http://datatables.net/reference/option/columns.data
     */
    public function setData($data)
    {
        if (is_string($data)) {
            $pattern = '/^(\s+)*(function)(\s+)*\(/i';
            if (preg_match($pattern, $data, $matches) && strtolower($matches[2]) == 'function') {
                $hash = sha1($data);
                $this->properties['data'] = $hash;
                $this->callbacks[$hash] = $data;

                return $this;
            }

            $this->properties['data'] = $data;

            return $this;
        } else {
            $this->properties['data'] = $data;

            return $this;
        }
    }

    /**
     * Get the data source for the column from the rows data object / array
     *
     * @return int|string|null|js object|callback
     * @see http://datatables.net/reference/option/columns.data
     */
    public function getData()
    {
        return $this->properties['data'];
    }

    /**
     * Often you may with to have static content in a column, for example simple edit and / or delete buttons,
     * which have events assigned to them. This option is available for those use cases - creating static
     * content for a column. If you wish to create dynamic content (i.e. based on other data in the row), the
     * columns.renderDT option should be used.
     *
     * @param string $defaultContent Set default, static, content for a column
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.defaultContent
     */
    public function setDefaultContent($defaultContent)
    {
        $this->properties['defaultContent'] = $defaultContent;

        return $this;
    }

    /**
     * When working with DataTables' API, it is very common to want to be able to address individual columns
     * so you can work with them (you wish to sum the numeric content of a column for example). DataTables
     * has two basic methods of addressing columns:
     *
     * As a column index (automatically assigned when the table is initialised)
     * With a name - assigned using this option!
     *
     * @param string $name Set a descriptive name for a column
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.name
     */
    public function setName($name)
    {
        $this->properties['name'] = $name;

        return $this;
    }

    /**
     * Using this parameter, you can remove the end user's ability to order upon a column. This might be
     * useful for generated content columns, for example if you have 'Edit' or 'Delete' buttons in the table.
     *
     * @param bool $orderable Enable or disable ordering on this column
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.orderable
     */
    public function isOrderable($orderable)
    {
        $this->properties['orderable'] = $orderable;

        return $this;
    }

    /**
     * Allows a column's sorting to take multiple columns into account when doing a order.
     *
     * For example with first name / last name columns next to each other, it is intuitive that they would be
     * linked together to multi-column sort.
     *
     * @param int|array $orderData Define multiple column ordering as the default order for a column.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.orderData
     */
    public function setOrderData($orderData)
    {
        $this->properties['orderData'] = $orderData;

        return $this;
    }


    /**
     * DataTables' primary order method (the orderingDT feature) makes use of data that has been
     * cached in memory rather than reading the data directly from the DOM every time an order is performed
     * for performance reasons (reading from the DOM is inherently slow). However, there are times when
     * you do actually want to read directly from the DOM, acknowledging that there will be a performance hit,
     * for example when you have form elements in the table and the end user can alter the values. This
     * configuration option is provided to allow plug-ins to provide this capability in DataTables.
     *
     * @param string $orderDataType Live DOM sorting type assignment.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.orderDataType
     */
    public function setOrderDataType($orderDataType)
    {
        $this->properties['orderDataType'] = $orderDataType;

        return $this;
    }

    /**
     * You can control the default ordering direction, and even alter the behaviour of the order handler (i.e.
     * only allow ascending sorting etc) using this parameter.
     *
     * @param array $orderSequence Order direction application sequence.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.orderSequence
     */
    public function setOrderSequence($orderSequence)
    {
        $this->properties['orderSequence'] = $orderSequence;

        return $this;
    }

    /**
     * This property is the rendering partner to columns.dataDT and it is suggested that when you want to
     * manipulate data for display (including filtering, sorting etc) without altering the underlying data for the
     * table, use this property. render can be considered to be the the read only companion to data which
     * is read / write (then as such more complex). Like data this option can be given in a number of
     * different ways to effect its behaviour as described below.
     *
     * @param int|string|object|callback $render Render (process) the data for use in the table.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.render
     */
    public function setRender($render)
    {
        if (is_string($render)) {
            $pattern = '/^(\s+)*(function)(\s+)*\(/i';
            if (preg_match($pattern, $render, $matches) && strtolower($matches[2]) == 'function') {
                $hash = sha1($render);
                $this->properties['render'] = $hash;
                $this->callbacks[$hash] = $render;
                return $this;
            }

            $this->properties['render'] = $render;
            return $this;
        } else {
            $this->properties['render'] = $render;
            return $this;
        }
    }

    /**
     * Using this parameter, you can defined if DataTables should include this column in the filterable data in
     * the table. You may want use this option to display filtering on generated columns such as 'Edit' and
     * 'Delete' buttons for example.
     *
     * @param bool $searchable Enable or disable filtering on the data in this column.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.searchable
     */
    public function isSearchable($searchable)
    {
        $this->properties['searchable'] = $searchable;

        return $this;
    }

    /**
     * The titles of columns are typically read directly from the DOM (from the cells in the THEAD element),
     * but it can often be useful to either override existing values, or have DataTables actually construct a
     * header with column titles for you (for example if there is not THEAD element in the table before
     * DataTables is constructed). This option is available to provide that ability.
     *
     * @param string $title Set the column title.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.title
     */
    public function setTitle($title)
    {
        $this->properties['title'] = $title;

        return $this;
    }

    /**
     * When working with the different types of data (filtering, sorting, display etc), DataTables can process
     * the data used for the display in each cell in a manual suitable for the function being performed. For
     * example, HTML tags will be removed from the strings used for filter matching, while sort formatting may
     * remove currency symbols to allow currency values to be sorted numerically.
     *
     * @param string $type Set the column type - used for filtering and sorting string processing.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.type
     */
    public function setType($type)
    {
        $this->properties['type'] = $type;

        return $this;
    }

    /**
     * DataTables and show and hide columns dynamically through use of this option and
     * the column().visible()DT / columns().visible()DT methods. This option can be used to get the
     * initial visibility state of the column, with the API methods used to alter that state at a later time.
     *
     * @param bool $visible Enable or disable the display of this column.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.visible
     */
    public function isVisible($visible)
    {
        $this->properties['visible'] = $visible;

        return $this;
    }

    /**
     * This parameter can be used to define the width of a column, and may take any CSS value (3em, 20px etc).
     *
     * @param string $width Column width assignment.
     *
     * @return Column
     * @see http://datatables.net/reference/option/columns.width
     */
    public function setWidth($width)
    {
        $this->properties['width'] = $width;

        return $this;
    }
}
