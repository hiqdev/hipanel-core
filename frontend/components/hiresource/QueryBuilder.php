<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace frontend\components\hiresource;
use yii\base\InvalidParamException;
use yii\base\NotSupportedException;
use yii\helpers\Json;
/**
 * QueryBuilder builds an hiresource query based on the specification given as a [[Query]] object.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class QueryBuilder extends \yii\base\Object
{
    private $_sort = [
        SORT_ASC =>'_asc',
        SORT_DESC=>'_desc',
    ];

    /**
     * @var Connection the database connection.
     */
    public $db;
    /**
     * Constructor.
     * @param Connection $connection the database connection.
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($connection, $config = [])
    {
        $this->db = $connection;
        parent::__construct($config);
    }
    /**
     * Generates query from a [[Query]] object.
     * @param Query $query the [[Query]] object from which the query will be generated
     * @return array the generated SQL statement (the first array element) and the corresponding
     * parameters to be bound to the SQL statement (the second array element).
     */
    public function build($query)
    {
        // \yii\helpers\VarDumper::dump($query, 10, true);
        $options = $parts = [];
        if ($query->limit !== null && $query->limit >= 0) {
            $parts['limit'] = $query->limit;
        }
        if ($query->offset > 0) {
            $parts['page'] = ceil($query->offset/$query->limit)+1;
        }
        if (!empty($query->query)) {
            $parts['query'] = $query->limit;
        }

        if (!empty($query->where)) {
            $parts = array_merge($query->where,$parts);
        }

        if (!empty($query->orderBy)) {
            $parts['orderby'] = key($query->orderBy).$this->_sort[reset($query->orderBy)];
        }
        // \yii\helpers\VarDumper::dump($parts, 10, true);
        return [
            'queryParts' => $parts,
            'index' => $query->index,
            'type' => $query->type,
            'options' => $options,
        ];
    }
    protected function buildCompositeInCondition($operator, $columns, $values)
    {
        throw new NotSupportedException('composite in is not supported by hiresource.');
    }
    private function buildLikeCondition($operator, $operands)
    {
        throw new NotSupportedException('like conditions are not supported by hiresource.');
    }
}