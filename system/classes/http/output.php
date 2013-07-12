<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * HTTP Output Class
 */
class HTTPOutput
{
	/**
	 * Headers container
	 */
	protected $headers = array();

	/**
	 * Data Stack
	 */
	protected $data = array();

	/**
	 * @cosntructor
	 */
	public function __construct()
	{
	}

	/**
	 * Clear the data from the store
	 */
	public function clear()
	{
		$this->data = array();
	}

	/**
	 * Set a key value pair to be passed to the output
	 * this can passed to a template or a json response for example.
	 */
	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function remove($key)
	{
		unset($this->data[$key]);
	}

	public function sendJSON($payload)
	{
		try
		{
			$this->setHeader("Content-Type", 'application/json');
			$this->send(json_encode($payload));
		}catch(Exception $e)
		{
			throw new Error("Data cannot be converted to JSON", 1, $e);
		}
	}

	public function send($data)
	{
		/**
		 * Flush the headers
		 */
		if(!headers_sent())
		{
			foreach ($this->headers as $key => $value) {
				header($key . ': ' . $value);
			}
		}

		/**
		 * print the data
		 */
		echo $data;
	}

	/**
	 * Set a header to the output.
	 */
	public function setHeader($key, $value)
	{
		$this->headers[$key] = $value;
	}

	/**
	 * Delete a header from out.
	 */
	public function deleteHeader($key)
	{
		unset($this->headers[$key]);
	}

	/**
	 * get a header from the headers
	 */
	public function getHeader($key)
	{
		return $this->headers[$key];
	}
}