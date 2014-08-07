<?php

namespace DataTable\Column;

use DataTable\Column;
use DataTable\Exception;
use DataTable\Table;

/**
 * Action column
 *
 * @package DataTable\Column
 */
class Action extends Column implements ColumnInterface
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var callable
     */
    protected $manager;

    /**
     * @var ActionBuilder
     */
    protected static $builder;

    /**
     * @var string
     */
    protected $template;

    const COLUMN_DATA = '___action_column___';

    /**
     * Action column constructor
     */
    public function __construct($template = 'Column/action.twig')
    {
        $this->template = $template;

        $this->isSearchable(false);
        $this->isOrderable(false);
        $this->setType(Column::TYPE_HTML);
        $this->setData(self::COLUMN_DATA);
    }

    /**
     * Initialize table
     *
     * @param Table $table Table object
     *
     * @return void
     */
    public function initialize(Table $table)
    {
        $this->table = $table;

        if (!self::$builder instanceof ActionBuilder) {
            self::$builder = new ActionBuilder($this->table);
        }
    }

    /**
     * Get column content
     *
     * @param mixed $rowResult Per row result
     *
     * @return string
     */
    public function getContent($rowResult)
    {
        if ($this->callManager($rowResult) === false) {
            return '';
        }

        return self::$builder->render($this->template);
    }

    /**
     * Set action manager
     *
     * @param callable $manager
     *
     * @throws \DataTable\Exception
     * @return Action
     */
    public function setManager($manager)
    {
        if (!is_callable($manager)) {
            throw new Exception('Manager must be callable.');
        }

        $this->manager = $manager;

        return $this;
    }

    /**
     * Call manager
     *
     * @param mixed $rowResult Per row result
     *
     * @return bool
     */
    protected function callManager($rowResult)
    {
        if (is_callable($this->manager)) {
            call_user_func_array($this->manager, [self::$builder, $rowResult]);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Set formatter\
     *
     * @param callable $formatter Formatter callable
     *
     * @return Action|void
     * @throws \DataTable\Exception
     */
    public function setFormatter($formatter)
    {
        throw new Exception('You must use "setManager" method.');
    }
}
