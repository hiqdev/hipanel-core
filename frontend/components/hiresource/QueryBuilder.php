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
            $whereFilter = $this->buildCondition($query->where);
            $parts = array_merge($parts, $whereFilter);
        }

        if (!empty($query->orderBy)) {
            $parts['orderby'] = key($query->orderBy).$this->_sort[reset($query->orderBy)];
        }
        print '<hr>';\yii\helpers\VarDumper::dump($parts, 10, true);print '<hr>';

        return [
            'queryParts' => $parts,
            'index' => $query->index,
            'type' => $query->type,
            'options' => $options,
        ];
    }

    public function buildCondition($condition) {

        static $builders = [
            'and'     => 'buildAndCondition',
            'between' => 'buildBetweenCondition',
            'in'      => 'buildInCondition',
            'like'    => 'buildLikeCondition',
        ];
        if (empty($condition)) {
            return [];
        }
        if (!is_array($condition)) {
            throw new NotSupportedException('String conditions in where() are not supported by hiresource.');
        }

        if (isset($condition[0])) { // operator format: operator, operand 1, operand 2, ...
            $operator = strtolower($condition[0]);
            if (isset($builders[$operator])) {
                $method = $builders[$operator];
                array_shift($condition);
                return $this->$method($operator, $condition);
            } else {
                throw new InvalidParamException('Found unknown operator in query: ' . $operator);
            }
        } else {
            return $this->buildHashCondition($condition);
        }
    }

    private function buildHashCondition($condition)
    {
        $parts = [];
        foreach ($condition as $attribute => $value) {
            if (is_array($value)) { // IN condition
                // $parts[] = [$attribute.'s' => join(',',$value)];
                $parts[$attribute.'s'] = join(',',$value);
            } else {
                $parts[$attribute] = $value;
            }
        }

        return $parts;
    }

    private function buildLikeCondition ($operator, $operands) {
        if (!isset($operands[0], $operands[1])) {
            throw new InvalidParamException("Operator '$operator' requires three operands.");
        }
        return [$operands[0].'_like' => $operands[1]];
    }

    private function buildAndCondition($operator, $operands)
    {
        $parts = [];
        foreach ($operands as $operand) {
            if (is_array($operand)) {
                $parts = \yii\helpers\ArrayHelper::merge($this->buildCondition($operand), $parts);
            }
        }
        if (!empty($parts)) {
            return $parts;
        } else {
            return [];
        }
    }

    private function buildBetweenCondition($operator, $operands)
    {
        throw new NotSupportedException('Between condition is not supported by hiresource.');
    }

    private function buildInCondition($operator, $operands)
    {
        throw new NotSupportedException('In is not supported by hiresource.');
    }

    protected function buildCompositeInCondition($operator, $columns, $values)
    {
        throw new NotSupportedException('composite in is not supported by hiresource.');
    }

}