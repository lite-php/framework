<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * HTTP Input Class
 */
class HTTPInput
{
	/**
	 * @cosntructor
	 */
	public function __construct()
	{
	}

	/**
	 * Return the request URI
	 */
	public function getScriptName()
	{
		return$_SERVER['SCRIPT_NAME'];
	}

	/**
	 * Return the request URI
	 */
	public function getRequestURI()
	{
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Return the request URI
	 */
	public function getQueryString()
	{
		return $_SERVER['QUERY_STRING'];
	}
}