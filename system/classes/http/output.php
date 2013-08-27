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
!defined('SECURE') && die('Access Forbidden!');

/**
 * HTTP Output Class
 */
class HTTPOutput
{
	/**
	 * Headers container
	 */
	protected $headers = array(
		'Content-Type' => 'text/html'
	);

	/**
	 * Initializes a new Output Object
	 */
	public function __construct()
	{
	}

	/**
	 * Clear the data from the store
	 */
	public function clear()
	{
		/**
		 * Reset the headers array
		 */
		$this->clearHeaders();
	}

	/**
	 * Set the content type header
	 * @param string $contentType
	 */
	public function setContentType($contentType)
	{
		$this->setHeader('Content-Type', $contentType);
	}

	/**
	 * Sends an object as a json string
	 * @param  *  $data
	 * @param  integer $options
	 */
	public function sendJSON($data, $options = 0)
	{
		/**
		 * encode the json
		 */
		$payload = json_encode($data, $options);

		/**
		 * Check to see wether we have a valid json string.
		 */
		if($payload === false)
		{
			throw new Exception("Unable to send json: " . json_last_error());
		}

		/**
		 * Set the application/josn header format
		 */
		$this->setContentType('application/json');

		/**
		 * Send the payload to the client
		 */
		$this->send($payload);
	}

	/**
	 * Send a payload of data tot he client
	 * @param  string $data
	 */
	public function send($data)
	{
		/**
		 * Send the headers
		 */
		$this->sendHeaders();

		/**
		 * print the data
		 */
		echo $data;
	}

	/**
	 * Check to see if the headers have been sent
	 * @return bool true|false depening on if the headers are sent.
	 */
	public function headersSent()
	{
		return headers_sent();
	}

	/**
	 * Send the headers if headers have not already been sent
	 * @return boolean
	 */
	public function sendHeaders()
	{
		if(!$this->headersSent())
		{
			foreach ($this->headers as $key => $value)
			{
				header($key . ': ' . $value);
			}

			return true;
		}

		return false;
	}

	/**
	 * Set a header to the output.
	 */
	public function clearHeaders()
	{
		$this->headers = array();
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
	public function removeHeader($key)
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

	/**
	 * Redirect method
	 */
	public function redirect($location, $terminate = true)
	{
		/**
		 * If the headers are sent, we need to throw an exception
		 */
		if($this->headersSent())
		{
			throw new Exception("Cannot redirect, headers are already sent.");
		}

		/**
		 * Add the header
		 */
		$this->setHeader('Location', $location);

		/**
		 * Terminate the request if required.
		 */
		if($terminate)
		{
			$this->send(null);
			exit;
		}
	}
}