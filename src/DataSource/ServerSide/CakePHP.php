<?php

namespace DataTable\DataSource\ServerSide;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use DataTable\Column\ColumnInterface;
use DataTable\DataSource\DataSourceInterface;
use DataTable\Exception;
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
                if ($column instanceof ColumnInterface) {
                    $row[$column->getData()] = $column->getContent($entity);
                    continue;
                }

                $property = $this->getProperty($column->getData());
                $columnData = explode('.', $column->getData());
                $tableAlias = $columnData[0];
                $colName = $columnData[1];

                /**
                 * If property is association
                 */
                if (is_array($property)) {
                    if (is_callable($column->getFormatter())) {
                        $row[$tableAlias][$colName] = $this->doFormatter(
                            $column->getFormatter(),
                            [
                                $entity->get($property['propertyPath']) instanceof Entity ?
                                    $entity->get($property['propertyPath'])->get($property['field']) :
                                    $entity->get($property['propertyPath']),
                                $entity,
                                $entity->get($property['propertyPath'])
                            ]
                        );
                    } else {
                        if ($entity->get($property['propertyPath']) instanceof Entity) {
                            $row[$tableAlias][$colName] =
                                (string)$entity->get($property['propertyPath'])->get($property['field']);
                            /**
                             * BelongsToMany Association
                             */
                        } elseif (is_array($entity->get($property['propertyPath']))) {
                            $output = [];
                            /** @var Entity $belongsToManyEntity */
                            foreach ($entity->get($property['propertyPath']) as $belongsToManyEntity) {
                                $output[] = $belongsToManyEntity->get($property['field']);
                            }

                            $row[$tableAlias][$colName] = implode(', ', $output);
                        }
                    }
                } else {
                    if (is_callable($column->getFormatter())) {
                        $row[$tableAlias][$colName] = $this->doFormatter(
                            $column->getFormatter(),
                            [$entity->get($property), $entity]
                        );
                    } else {
                        $row[$tableAlias][$colName] = (string)$entity->get($property);
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
            $where = [];
            foreach ($request->getColumns() as $column) {
                if ($column->getSearchable() === true) {
                    $where[$column->getData() . ' LIKE'] = '%' . $value . '%';
                }
            }

            $this->query->andWhere(
                function (QueryExpression $exp) use ($where) {
                    return $exp->or_($where);
                }
            );
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
     * @throws Exception
     * @return array|string
     */
    protected function getProperty($dataName)
    {
        $dataName = explode('.', trim($dataName));

        if (count($dataName) != 2) {
            throw new Exception('You are set invalid date.');
        }

        $tableAlias = $dataName[0];
        $colName = $dataName[1];

        if ($this->query->repository()->alias() == $tableAlias) {
            return $colName;
        } elseif (array_key_exists($tableAlias, $this->query->contain())) {
            return [
                'propertyPath' => $this->query
                    ->eagerLoader()->normalized($this->query->repository())[$tableAlias]['propertyPath'],
                'field' => $colName
            ];
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
