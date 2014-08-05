<?php

namespace DataTable\DataSource\ServerSide;

use Cake\ORM\Entity;
use Cake\ORM\Query;
use DataTable\DataSource\DataSourceInterface;
use DataTable\Request;
use DataTable\Response;
use DataTable\Table;

/**
 * CakePHP Data Source
 *
 * @package DataTable\DataSource\ServerSide
 */
class CakePHP implements DataSourceInterface, ServerSideInterface
{
    /**
     * @var Query
     */
    protected $query;

    /**
     * @var Query
     */
    protected $clonedQuery;

    /**
     * @var Table
     */
    protected $table;

    /**
     * @var string
     */
    protected $ajax;
    protected static $countBeforePaging;

    /**
     * CakePHP data source constructor
     *
     * @param Query  $query CakePHP query object
     * @param string $ajax  Load data for the table's content from an Ajax source.
     */
    public function __construct(Query $query = null, $ajax = null)
    {
        if (!is_null($query)) {
            $this->setQuery($query);
        }

        if (!is_null($ajax)) {
            $this->ajax = $ajax;
        }
    }

    /**
     * Initialize data source
     *
     * @param Table $table Table object
     *
     * @return void
     */
    public function initialize(Table $table)
    {
        $this->table = $table;
        $this->table->isProcessing(true);
        $this->table->isServerSide(true);

        if (!is_null($this->ajax)) {
            $this->table->setAjax($this->ajax);
        }
    }

    /**
     * Get DataTable response
     *
     * @param Response $response DataTable response object
     * @param Request  $request  DataTable request object
     *
     * @return void
     */
    public function getResponse(Response $response, Request $request)
    {
        $this->prepareSearch($request);
        $this->preparePaging($request);
        $this->prepareOrder($request);

        $data = [];
        /** @var $entity Entity */
        foreach ($this->query as $entity) {
            $row = [];
            foreach ($this->table->getColumns() as $column) {
                $property = $this->getProperty($column->getData());
                $columnData = explode('.', $column->getData());

                /**
                 * If property is association
                 */
                if (is_array($property)) {
                    if (is_callable($column->getFormatter())) {
                        $row[$columnData[0]][$columnData[1]] = $this->doFormatter(
                            $column->getFormatter(),
                            [
                                $entity->get($property['propertyPath'])->get($property['field']),
                                $entity,
                                $entity->get($property['propertyPath'])
                            ]
                        );
                    } else {
                        $row[$columnData[0]][$columnData[1]] = (string)$entity->get($property['propertyPath'])->get(
                            $property['field']
                        );
                    }
                } else {
                    if (is_callable($column->getFormatter())) {
                        $row[$columnData[0]][$columnData[1]] = $this->doFormatter(
                            $column->getFormatter(),
                            [$entity->get($property), $entity]
                        );
                    } else {
                        $row[$columnData[0]][$columnData[1]] = (string)$entity->get($property);
                    }
                }
            }
            $data[] = $row;
        }

        $response->setDraw($request->getDraw())
            ->setRecordsTotal($this->clonedQuery->count())
            ->setRecordsFiltered(self::$countBeforePaging)
            ->setData($data);
    }

    /**
     * Set CakePHP query
     *
     * @param Query $query CakePHP query
     *
     * @return CakePHP
     */
    public function setQuery(Query $query)
    {
        $this->query = $query;
        $this->clonedQuery = clone $query;

        return $this;
    }

    /**
     * Prepare CakePHP paging
     *
     * @param Request $request DataTable request
     */
    protected function preparePaging(Request $request)
    {
        self::$countBeforePaging = $this->query->count();
        $this->query->limit($request->getLength());
        $this->query->offset($request->getStart());
    }

    /**
     * Prepare CakePHP search
     *
     * @param Request $request DataTable request
     */
    protected function prepareSearch(Request $request)
    {
        $value = $request->getSearch()->getValue();

        if (!empty($value)) {
            foreach ($request->getColumns() as $column) {
                if ($column->getSearchable() === true) {
                    $this->query->orWhere(
                        [$this->table->getColumns()[$column->getData()]->getData() . ' LIKE' => '%' . $value . '%']
                    );
                }
            }
        }
    }

    /**
     * Prepare CakePHP order
     *
     * @param Request $request DataTable request
     */
    protected function prepareOrder(Request $request)
    {
        foreach ($request->getOrder() as $order) {
            $this->query->order(
                [$this->table->getColumns()[$order->getColumn()]->getData() => strtoupper($order->getDir())]
            );
        }
    }

    /**
     * Get CakePHP property
     *
     * @param string $dataName Column data name
     *
     * @return array|string
     */
    protected function getProperty($dataName)
    {
        $dataName = explode('.', $dataName);

        if (count($dataName) == 2 && array_key_exists($dataName[0], $this->query->contain())) {
            return [
                'propertyPath' => $this->query
                        ->eagerLoader()->normalized(
                            $this->query->repository()
                        )[$dataName[0]]['propertyPath'],
                'field' => $dataName[1]
            ];
        } else {
            return $dataName[1];
        }
    }

    /**
     * Do formatter
     *
     * @param callable $formatter Formatter callback
     * @param array    $args      Formatter args
     *
     * @return mixed
     */
    protected function doFormatter($formatter, array $args)
    {
        return call_user_func_array($formatter, $args);
    }
}
