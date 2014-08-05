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
    protected $twig;
    protected $templatePath;

    const DS = DIRECTORY_SEPARATOR;

    public function __construct(Table $table)
    {
        $this->table = $table;
        $this->templatePath = dirname(__FILE__) . self::DS . 'Template';

        $loader = new Twig_Loader_Filesystem($this->templatePath);
        $this->twig = new Twig_Environment($loader, ['debug' => $table->getDebugMode()]);
        $this->twig->addGlobal('table', $this->table);
        $this->twig->addGlobal('render', $this);

        if ($table->getDebugMode()) {
            $this->twig->addExtension(new Twig_Extension_Debug());
        }
    }

    public function render()
    {
        return $this->twig->render('main.twig', ['config' => $this->getConfig()]);
    }

    public function hasTableHtmlTag()
    {
        if (!$this->table->getDataSource() instanceof Dom) {
            return true;
        } else {
            return false;
        }
    }

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

    protected function prepareColumnsConfig()
    {
        $columns = [];

        foreach ($this->table->getColumns() as $column) {
            $columns[] = $column->getProperties();
        }

        $this->table->setColumns($columns);
    }

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
