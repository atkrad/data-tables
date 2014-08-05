<?php

namespace DataTable\Extension;

/**
 * Extension Interface
 *
 * @package DataTable
 */
interface ExtensionInterface
{
    /**
     * Get dom name
     *
     * @return string
     */
    public function getDomName();

    /**
     * Get property name
     *
     * @return string
     */
    public function getPropertyName();

    /**
     * Get extension properties
     *
     * @return array
     */
    public function getProperties();

    /**
     * Get extension callbacks
     *
     * @return array
     */
    public function getCallbacks();
}
