<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\components\hiresource;

use yii\base\Component;
use yii\base\InvalidCallException;
use yii\helpers\Json;

/**
 * The Command class implements the API for accessing the elasticsearch REST API.
 *
 * Check the [elasticsearch guide](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/index.html)
 * for details on these commands.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class Command extends Component
{
    /**
     * @var Connection
     */
    public $db;
    /**
     * @var string|array the indexes to execute the query on. Defaults to null meaning all indexes
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/search.html#search-multi-index
     */
    public $index;
    /**
     * @var string|array the types to execute the query on. Defaults to null meaning all types
     */
    public $type;
    /**
     * @var array list of arrays or json strings that become parts of a query
     */
    public $queryParts;
    public $options = [];


    /**
     * Sends a request to the _search API and returns the result
     * @param array $options
     * @return mixed
     */
    public function search($options = [])
    {
        $query = $this->queryParts;
//        if (empty($query)) {
//            $query = '{}';
//        }
//        if (is_array($query)) {
//            $query = Json::encode($query);
//        }
        $options = array_merge($query, $options);
        $url = $this->index.'Search';

        return $this->db->get($url, $options);
    }

    public function getList($options = [])
    {
        $options = array_merge($this->queryParts, $options);
        return $this->db->get($this->index.'GetList', $options);
    }



    public function insert($action, $data, $id = null, $options = [])
    {
//        if (empty($data)) {
//            $body = '{}';
//        } else {
//            $body = is_array($data) ? Json::encode($data) : $data;
//        }
        $options = array_merge($data, $options);

        if ($id !== null) {
            return $this->db->put($action.'Update', array_merge($options,['id'=>$id]), $body);
        } else {
            return $this->db->post($action.'Create', $options, $body);
        }
    }

    public function get()
    {
        unset($this->queryParts['limit']);
        return $this->db->get($this->type.'GetInfo', $this->queryParts);
    }

    public function mget($index, $type, $ids, $options = [])
    {
        $body = Json::encode(['ids' => array_values($ids)]);

        return $this->db->get([$index, $type, '_mget'], $options, $body);
    }

    public function exists($index, $type, $id)
    {
        return $this->db->head([$index, $type, $id]);
    }

    public function delete($index, $id, $options = [])
    {
        return $this->db->delete($index.'Delete', array_merge($options,['id'=>$id]));
    }

	public function update($index, $type, $id, $data, $options = [])
	{
		// TODO implement
        return $this->db->delete([$index, $type, $id], $options);
	}

}