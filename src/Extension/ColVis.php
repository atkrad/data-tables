<?php

namespace DataTable\Extension;

use ArrayAccess;
use DataTable\ArrayAccessTrait;

/**
 * ColVis Extension
 *
 * ColVis adds a button to the toolbars around DataTables which gives
 * the end user of the table the ability to dynamically change the
 * visibility of the columns in the table
 *
 * @package DataTable\Extension
 */
class ColVis implements ExtensionInterface, ArrayAccess
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

    const DOM_NAME = 'C';
    const PROPERTY_NAME = 'colVis';

    const ACTIVATE_MOUSE_OVER = 'mouseover';
    const ACTIVATE_CLICK = 'click';

    const ALIGN_LEFT = 'left';
    const ALIGN_RIGHT = 'right';

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
     * This parameter denotes how the dropdown list of columns can be
     * activated by the end user. Its value should be either "mouseover" or "click".
     *
     * @param string $activate This parameter denotes how the dropdown list of columns can be activated by the end user.
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setActivate($activate)
    {
        $this->properties['activate'] = $activate;

        return $this;
    }

    /**
     * This array contains the column indexes which you wish to exclude from the list
     * of columns in the dropdown list, effectively meaning that the end user has no
     * control over the visibility of those columns. As well as indexes, this array
     * can also contain the string 'all' which indicates that all columns should be
     * excluded from the list. This is useful when using ColVis' grouping buttons feature.
     *
     * @param array $exclude This array contains the column indexes which you wish to exclude from the list.
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setExclude($exclude)
    {
        $this->properties['exclude'] = $exclude;

        return $this;
    }

    /**
     * The text that will be used in the button.
     *
     * @param string $buttonText The text that will be used in the button.
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setButtonText($buttonText)
    {
        $this->properties['buttonText'] = $buttonText;

        return $this;
    }

    /**
     * Allows customisation of the labels used for the buttons (useful for stripping HTML for example).
     *
     * Input parameters:
     *
     * 1) int: The column index being operated on
     * 2) string: The title detected by DataTables' automatic methods.
     * 3) node: The TH element for the column
     *
     * Return parameter:    string: The value to use for the button table
     *
     * @param callback $label Allows customisation of the labels used for the buttons.
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setLabel($label)
    {
        $hash = sha1($label);
        $this->properties['label'] = $hash;
        $this->callbacks[$hash] = $label;

        return $this;
    }

    /**
     * Set callback function to let you know when the state has changed.
     *
     * @param callback $stateChange Callback function to let you know when the state has changed.
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setStateChange($stateChange)
    {
        $hash = sha1($stateChange);
        $this->properties['stateChange'] = $hash;
        $this->callbacks[$hash] = $stateChange;

        return $this;
    }

    /**
     * Alter the duration used for the fade in / out animation of the column
     * visibility buttons when the control button is clicked on. The value of
     * the parameter is interpreted as milliseconds.
     *
     * @param int $overlayFade Alter the duration used for the fade in / out animation of the column
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setOverlayFade($overlayFade = 500)
    {
        $this->properties['overlayFade'] = $overlayFade;

        return $this;
    }

    /**
     * This parameter provides the ability to specify which edge of the control
     * button the drop down column visibility list should align to - either "left" or "right"
     *
     * @param string $align
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setAlign($align = self::ALIGN_LEFT)
    {
        $this->properties['align'] = $align;

        return $this;
    }

    /**
     * This parameter is used to enable a button that, when selected, will
     * show all columns in the table. The value given is used as the button's display text.
     *
     * @param string $showAll The value given is used as the button's display text.
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setShowAll($showAll)
    {
        $this->properties['showAll'] = $showAll;

        return $this;
    }

    /**
     * This parameter is used to enable a button that, when selected, will hide
     * all columns in the table. The value given is used as the button's display text.
     *
     * @param string $showNone The value given is used as the button's display text.
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setShowNone($showNone)
    {
        $this->properties['showNone'] = $showNone;

        return $this;
    }

    /**
     * This parameter provides the ability to customise the text for the restore
     * button. Note that when using camelCase style, if this parameter is
     * defined, it is assumed that the restore button should be shown.
     *
     * @param string $restore If this parameter is defined, it is assumed that the restore button should be shown.
     *
     * @return ColVis
     * @see http://datatables.net/extensions/colvis/options
     */
    public function setRestore($restore)
    {
        $this->properties['restore'] = $restore;

        return $this;
    }
}
