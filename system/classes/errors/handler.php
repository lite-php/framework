<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
!defined('SECURE') && die('Access Forbidden!');

/**
 * Error Handler Class
 */
class ErrorHandler
{
	/**
	 * @type ErrorHandler static class holder.
	 */
	private static $__instance;

	/**
	 * Return an instance of the exception handler
	 */
	public static function GetInstanCe()
	{
		if(!self::$__instance)
		{
			self::$__instance = new ErrorHandler();
		}

		return self::$__instance;
	}

	public function __construct()
	{
		/**
		 * Set the exception handler
		 */
		set_exception_handler(array($this, 'handleException'));

		/**
		 * Set the error handler
		 */
		set_error_handler(array($this, 'handleError'));
	}

	/**
	 * Handles errors throw my the system or application
	 */
	public function handleError($errno, $errstr, $errfile, $errline)
	{
		throw new Exception($errstr, $errno);
	}

	/**
	 * Handles exceptions throw my the system or application
	 */
	public function handleException(Exception $e)
	{
		/**
		 * @todo: Extend this functionality.
		 */
		var_dump($e);die();
		throw $e;
	}
}