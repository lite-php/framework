<?php
/**
 * LightPHP Framework
 * LitePHP is a framework that has been designed to be lite waight, extensible and fast.
 * 
 * @author Robert Pitt <robertpitt1988@gmail.com>
 * @category core
 * @copyright 2013 Robert Pitt
 * @license GPL v3 - GNU Public License v3
 * @version 1.0.0
 */

/**
 * This is a simple and lite github api for v1.0.0
 */
class Github_Model
{
	/**
	 * Github aqpi endpoint (currently v3)
	 */
	protected $endpoint = 'https://api.github.com';

	/**
	 * Cosntructor
	 */
	public function __construct()
	{
	}

	/**
	 * check to see if a repository exists
	 */
	public function exists($user, $repository)
	{
		/**
		 * Build therequest url
		 * @var strings
		 */
		$url = $this->endpoint . '/repos/' . $user . '/' . $repository;

		/**
		 * Call the api
		 * @var array
		 */
		$data = $this->__fetch($url, 'HEAD');

		/**
		 * Check to see if it exists
		 */
		return $data->info['http_code'] == 200;
	}

	/**
	 * check to see if a repository exists
	 */
	public function getInfo($user, $repository)
	{
		/**
		 * Build therequest url
		 * @var strings
		 */
		$url = $this->endpoint . '/repos/' . $user . '/' . $repository;

		/**
		 * Call the api
		 * @var array
		 */
		$data = $this->__fetch($url, 'GET');

		/**
		 * Check to see if it exists
		 */
		return $data->result;
	}

	/**
	 * check to see if a repository exists
	 */
	public function getBranches($user, $repository)
	{
		/**
		 * Build therequest url
		 * @var strings
		 */
		$url = $this->endpoint . '/repos/' . $user . '/' . $repository . '/branches';

		/**
		 * Call the api
		 * @var array
		 */
		$data = $this->__fetch($url, 'GET');

		/**
		 * Check to see if it exists
		 */
		return $data->result;
	}

	/**
	 * Fetch data from a web endpoint
	 * @return string data received
	 */
	public function __fetch($uri, $method = 'GET', $data = null, $curl_headers = array(), $curl_options = array())
	{
		/**
		 * Default curl options
		 * @var array
		 */
		$default_curl_options = array(
			CURLOPT_SSL_VERIFYPEER => false,
			//CURLOPT_HEADER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 3,
		);

		/**
		 * Defualt headers
		 * @var array
		 */
		$default_headers = array();
 
		/**
		 * Uppurcase and trim the method
		 * @var string
		 */
		$method = strtoupper(trim($method));

		/**
		 * Allowed methods
		 * @var array
		 */
		$allowed_methods = array('GET', 'POST', 'PUT', 'DELETE', 'HEAD');
 
 		/**
 		 * Check for allowed methods
 		 */
		if(!in_array($method, $allowed_methods))
			throw new Exception("'$method' is not valid cURL HTTP method.");

		/**
		 * Validate the data
		 */
		if(!empty($data) && !is_string($data))
			throw new Exception("Invalid data for cURL request '$method $uri'");
 
		/**
		 * Create a new curl object
		 * @var CurlObject
		 */
		$curl = curl_init($uri);

		/**
		 * Set the curl options
		 */
		curl_setopt_array($curl, $default_curl_options);

		/**
		 * Handle specific operations for the method type
		 */
		switch($method)
		{
			case 'GET':
			break;
			case 'POST':
				if(!is_string($data))
					throw new \Exception("Invalid data for cURL request '$method $uri'");
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
			case 'PUT':
				if(!is_string($data))
					throw new \Exception("Invalid data for cURL request '$method $uri'");
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
			case 'HEAD':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($curl, CURLOPT_NOBODY, true);
			break;
			case 'DELETE':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
			break;
		}
 
		/**
		 * Setthe curl options up
		 */
		curl_setopt_array($curl, $curl_options);

		/**
		 * Pass a set of merged headers to the curl object
		 */
		curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($default_headers, $curl_headers));

		/**
		 * Declare a response variable to return to the caller
		 * @var array
		 */
		$response = array();

		/**
		 * Execute the request
		 * @var string
		 */
		$response['result'] = curl_exec($curl);

		/**
		 * If we have a result, attempt to decode it as json
		 */
		try
		{
			$response['result'] = json_decode($response['result']);
		}catch(Exception $e){}

		/**
		 * Fetch the header code
		 * @var int
		 */
		$response['info'] = curl_getinfo($curl);

		/**
		 * Closee the curl result off
		 */
		curl_close($curl);

		// return
		return (object)$response;
	}
}