<?php
/**
 * ElasticSearch Library
 *
 * @package   Core PHP
 * @author    Atish Amte
 * @copyright Copyright (c) 2019, Atish Amte.
 * @version   Version 1.0
 */

namespace atishamte\ElasticSearch;

class ElasticSearch
{
	private $index = null;
	private $server = null;

	/**
	 * Constructor.
	 *
	 * @param string $server
	 */
	public function __construct(string $server)
	{
		if ($server === null || trim($server) == '')
		{
			exit('Server is not provided');
		}

		$this->server = $server;

	}

	/**
	 * Set the index
	 *
	 * @param string $index
	 *
	 * @return ElasticSearch
	 */
	public function set_index(string $index)
	{
		if ($index === null || trim($index) == '')
		{
			exit('Index is not provided');
		}
		$this->index = $index;

		return $this;
	}

	/**
	 * To create a index with mapping or not
	 *
	 * @param string     $index
	 * @param array|bool $map
	 *
	 * @return array
	 */
	public function create_index(string $index, $map = false)
	{
		$this->set_index($index);

		if (!$map)
		{
			return $this->_call(null, 'PUT');
		}

		return $this->_call(null, 'PUT', $map);
	}

	/**
	 * Delete the index
	 *
	 * @return array
	 */
	public function delete_index()
	{
		return $this->_call(null, 'DELETE');
	}

	/**
	 * Check index is available
	 *
	 * @return array
	 */
	public function check_index()
	{
		return $this->_call();
	}

	/**
	 * Get status
	 *
	 * @return array
	 */
	public function status()
	{
		return $this->_call('_stats');
	}

	/**
	 * Get the index count
	 *
	 * @return array
	 */
	public function count_all()
	{
		return $this->_call('_count', 'GET', '{"query":{"match_all":{}}}');
	}

	/**
	 * Add the document
	 *
	 * @param string $id
	 * @param array  $data
	 *
	 * @return array
	 */
	public function add(string $id, array $data)
	{
		return $this->_call('_create/' . $id, 'PUT', $data);
	}

	/**
	 * Update the document
	 *
	 * @param string $id
	 * @param array  $data
	 *
	 * @return array
	 */
	public function update(string $id, array $data)
	{
		return $this->_call('_update/' . $id, 'POST', $data);
	}

	/**
	 * delete the document
	 *
	 * @param string $id
	 *
	 * @return array
	 */
	public function delete(string $id)
	{
		return $this->_call('_doc/' . $id, 'DELETE');
	}

	/**
	 * Fetch single document by id
	 *
	 * @param string $id
	 *
	 * @return array
	 */
	public function get(string $id)
	{
		return $this->_call('_doc/' . $id);
	}

	/**
	 * To set the mapping for the document
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function map(array $data)
	{
		$query_param = ['properties' => $data];
		return $this->_call('_mapping', 'PUT', $query_param);
	}

	/**
	 * Search by query
	 *
	 * @param array   $query
	 * @param integer $size
	 *
	 * @return array
	 */
	public function query(array $query, int $size = 10)
	{
		$query_param = [
			'query' => $query,
			'size' => $size,
		];

		return $this->_call('_search', 'POST', $query_param);
	}

	/**
	 * To get all similar documents
	 *
	 * @param array $fields
	 * @param array $data
	 * @param int   $size
	 *
	 * @return array
	 */
	public function more_like_this(array $fields, array $data, int $size = 10)
	{
		if (empty(array_filter($fields)))
		{
			exit('Empty field array not allowed');
		}

		if (empty(array_filter($data)))
		{
			exit('Empty data array not allowed');
		}

		$filter = [
			'query' => [
				'more_like_this' => [
					'fields' => $fields,
					'like' => $data,
					'min_term_freq' => 1,
					'min_doc_freq' => 1,
				],
			],
			'size' => $size,
		];

		return $this->_call('_search', 'POST', $filter);
	}

	/**
	 * Function for every action invoked
	 *
	 * @param string $path
	 * @param string $method
	 * @param array  $data
	 *
	 * @return array
	 */
	private function _call($path = null, string $method = 'GET', $data = null)
	{
		$url = $this->server . '/' . $this->index . '/' . $path;
		$method = strtoupper($method);
		$headers = ['Accept: application/json', 'Content-Type: application/json'];

		if (function_exists('curl_version'))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			switch ($method)
			{
				case 'GET' :
					break;
				case 'POST' :
					curl_setopt($ch, CURLOPT_POST, true);
					if ($data !== null)
					{
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					}
					break;
				case 'PUT' :
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
					if ($data !== null)
					{
						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					}
					break;
				case 'DELETE' :
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
					break;
				default:
					exit('Invalid method passed');
			}

			$response = curl_exec($ch);

			return json_decode($response, true);
		}
		else
		{
			exit('cURL is not enabled on this server');
		}
	}
}

