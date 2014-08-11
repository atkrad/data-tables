<?php

namespace DataTable;

use DataTable\DataSource\Dom;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

/**
 * Render DataTable Class
 *
 * @package DataTable
 */
class Render
{
    /**
     * @var Table
     */
    protected $table;
    /**
     * @var Twig_Environment
     */
    protected $twig;
    protected $templatePath;

    const DS = DIRECTORY_SEPARATOR;

    /**
     * Table render constructor
     *
     * @param Table            $table Table object
     * @param Twig_Environment $twig  Twig object
     */
    public function __construct(Table $table, Twig_Environment $twig = null)
    {
        $this->table = $table;
        $this->templatePath = dirname(__FILE__) . self::DS . 'Template';

        if (is_null($twig)) {
            $loader = new Twig_Loader_Filesystem($this->templatePath);
            $this->twig = new Twig_Environment($loader, ['debug' => $table->isDebug()]);
        } else {
            $this->twig = $twig;
        }

        $this->twig->addGlobal('table', $this->table);
        $this->twig->addGlobal('render', $this);

        if ($table->isDebug()) {
            $this->twig->addExtension(new Twig_Extension_Debug());
        }
    }

    /**
     * Main render
     *
     * @param string $template Main template
     *
     * @return string
     */
    public function render($template)
    {
        return $this->twig->render($template, ['config' => $this->getConfig()]);
    }

    /**
     * Render table
     *
     * @param string $template Table template
     *
     * @return string
     */
    public function renderTable($template = 'table.twig')
    {
        return $this->twig->render($template, ['config' => $this->getConfig()]);
    }

    /**
     * Render js
     *
     * @param string $template JS template
     *
     * @return string
     */
    public function renderJs($template = 'js.twig')
    {
        return $this->twig->render($template, ['config' => $this->getConfig()]);
    }

    /**
     * Has table HTML tag
     *
     * @return bool
     */
    public function hasTableHtmlTag()
    {
        if (!$this->table->getDataSource() instanceof Dom) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get twig
     *
     * @return Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Get config
     *
     * @return string
     */
    protected function getConfig()
    {
        if (!$this->table->getDataSource() instanceof Dom) {
            $this->prepareColumnsConfig();
        }
        $this->prepareExtensionsConfig();

        $config = [];
        $config += $this->table->getProperties();

        return json_encode($config);
    }

    /**
     * Prepare columns config
     */
    protected function prepareColumnsConfig()
    {
        $columns = [];

        foreach ($this->table->getColumns() as $column) {
            $columns[] = $column->getProperties();
        }

        $this->table->setColumns($columns);
    }

    /**
     * Prepare extensions config
     */
    protected function prepareExtensionsConfig()
    {
        foreach ($this->table->getExtensions() as $extension) {
            $properties = $extension->getProperties();
            if (isset($properties)) {
                $this->table->setProperty($extension->getPropertyName(), $properties);
            }
        }
    }
}
