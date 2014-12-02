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

        return $this->db->get($url, array_merge($this->options, $options));
    }


    /**
     * Inserts a document into an index
     * @param string $index
     * @param string $type
     * @param string|array $data json string or array of data to store
     * @param null $id the documents id. If not specified Id will be automatically chosen
     * @param array $options
     * @return mixed
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-index_.html
     */
    public function insert($index, $type, $data, $id = null, $options = [])
    {
        if (empty($data)) {
            $body = '{}';
        } else {
            $body = is_array($data) ? Json::encode($data) : $data;
        }

        if ($id !== null) {
            return $this->db->put([$index, $type, $id], $options, $body);
        } else {
            return $this->db->post([$index, $type], $options, $body);
        }
    }

    /**
     * gets a document from the index
     * @param $index
     * @param $type
     * @param $id
     * @param array $options
     * @return mixed
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-get.html
     */
    public function get($action, $id, $options = [])
    {
        return $this->db->get([$action.'GetInfo'], array_merge($options,['id'=>$id]));
    }

    /**
     * gets multiple documents from the index
     *
     * TODO allow specifying type and index + fields
     * @param $index
     * @param $type
     * @param $ids
     * @param array $options
     * @return mixed
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-multi-get.html
     */
    public function mget($index, $type, $ids, $options = [])
    {
        $body = Json::encode(['ids' => array_values($ids)]);

        return $this->db->get([$index, $type, '_mget'], $options, $body);
    }

    /**
     * gets a document from the index
     * @param $index
     * @param $type
     * @param $id
     * @return mixed
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-get.html
     */
    public function exists($index, $type, $id)
    {
        return $this->db->head([$index, $type, $id]);
    }

    /**
     * deletes a document from the index
     * @param $index
     * @param $type
     * @param $id
     * @param array $options
     * @return mixed
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-delete.html
     */
    public function delete($index, $type, $id, $options = [])
    {
        return $this->db->delete([$index, $type, $id], $options);
    }

    /**
     * updates a document
     * @param $index
     * @param $type
     * @param $id
     * @param array $options
     * @return mixed
     * @see http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-update.html
     */
	public function update($index, $type, $id, $data, $options = [])
	{
		// TODO implement
        return $this->db->delete([$index, $type, $id], $options);
	}

}