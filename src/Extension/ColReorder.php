<?php

namespace DataTable\Extension;

use ArrayAccess;
use DataTable\ArrayAccessTrait;

/**
 * ColReorder Extension
 *
 * ColReorder adds the ability for the end user to click and drag
 * column headers to reorder a table as they see fit, to DataTables.
 *
 * @package DataTable\Extension
 */
class ColReorder implements ExtensionInterface, ArrayAccess
{
    use ArrayAccessTrait;

    /**
     * ColVis properties
     *
     * @var array
     */
    protected $properties = [];

    /**
     * ColVis callbacks
     *
     * @var array
     */
    protected $callbacks = [];

    const DOM_NAME = 'R';
    const PROPERTY_NAME = 'colReorder';

    /**
     * Get dom name
     *
     * @return string
     */
    public function getDomName()
    {
        return self::DOM_NAME;
    }

    /**
     * Get property name
     *
     * @return string
     */
    public function getPropertyName()
    {
        return self::PROPERTY_NAME;
    }

    /**
     * Get extension properties
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Get extension callbacks
     *
     * @return array
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * This parameter provides the ability to reorder a table on initialisation of
     * the DataTable. It's simply an array of column indexes, in the order that
     * you wish them to be displayed in.
     *
     * @param array $order This parameter provides the ability to reorder a table on initialisation of the DataTable.
     *
     * @return ColReorder
     * @see http://datatables.net/extensions/colreorder/options
     */
    public function setOrder($order)
    {
        $this->properties['order'] = $order;

        return $this;
    }

    /**
     * Callback function which can be used to perform actions when the columns have
     * been reordered.
     *
     * Input parameters:    void
     * Return parameter:    void
     *
     * @param string $reorderCallback Callback function which can be used to perform
     *                                actions when the columns have been reordered.
     *
     * @return ColReorder
     * @see http://datatables.net/extensions/colreorder/options
     */
    public function setReorderCallback($reorderCallback)
    {
        $hash = sha1($reorderCallback);
        $this->properties['reorderCallback'] = $hash;
        $this->callbacks[$hash] = $reorderCallback;

        return $this;
    }

    /**
     * Indicate how many columns should be fixed in position (counting from the right).
     * This will typically be 1 if used, but can be as high as you like.
     *
     * @param int $fixedColumnsRight Indicate how many columns should be fixed in position (counting from the right).
     *
     * @return ColReorder
     * @see http://datatables.net/extensions/colreorder/options
     */
    public function setFixedColumnsRight($fixedColumnsRight = 0)
    {
        $this->properties['fixedColumnsRight'] = $fixedColumnsRight;

        return $this;
    }

    /**
     * Indicate how many columns should be fixed in position (counting from the left).
     * This will typically be 1 if used, but can be as high as you like.
     *
     * @param int $fixedColumns Indicate how many columns should be fixed in position (counting from the left).
     *
     * @return ColReorder
     * @see http://datatables.net/extensions/colreorder/options
     */
    public function setFixedColumns($fixedColumns = 0)
    {
        $this->properties['fixedColumns'] = $fixedColumns;

        return $this;
    }
}
