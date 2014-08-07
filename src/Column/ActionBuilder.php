<?php

namespace DataTable\Column;

use DataTable\Table;
use DataTable\Render;

/**
 * Action Builder
 *
 * @package DataTable\Column
 */
class ActionBuilder
{
    /**
     * @var Table
     */
    protected $table;
    protected $actions = [];

    /**
     * Action builder constructor
     *
     * @param Table $table Table object
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * Add Action
     *
     * @param string $slug    Action slug
     * @param string $name    Action name
     * @param string $url     Action url
     * @param array  $options Action options
     *
     * @return ActionBuilder
     */
    public function addAction($slug, $name, $url, $options = [])
    {
        $this->actions[$slug] = ['name' => $name, 'url' => $url, 'options' => $options];

        return $this;
    }

    /**
     * Remove action
     *
     * @param string $slug Action slug
     *
     * @return bool
     */
    public function removeAction($slug)
    {
        if (array_key_exists($slug, $this->actions)) {
            unset($this->actions[$slug]);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all actions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Actions render
     *
     * @param string $template Action template name
     *
     * @return string
     */
    public function render($template)
    {
        $render = new Render($this->table);
        return $render->getTwig()->render($template, ['actions' => $this->getActions()]);
    }
}
