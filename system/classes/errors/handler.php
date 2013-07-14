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
 * Error Handler Class
 */
class ErrorHandler
{
	/**
	 * Static ErrorHandler instance varaible, used for tracking a single instance
	 * og this object.
	 * @type ErrorHandler static class holder.
	 */
	private static $__instance;

	/**
	 * Return an instance of the exception handler
	 */
	public static function getInstance()
	{
		if(!self::$__instance)
		{
			self::$__instance = new ErrorHandler();
		}

		return self::$__instance;
	}

	/**
	 * Instnatiate a new ErrorHandler object.
	 * @see ErrorHandler::getInstance
	 */
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

		/**
		 * Set the error reporting level.
		 */
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
		 * Check to see if we can dispose this error
		 */
		
		/**
		 * Attaempt tp clear out any buffers
		 */
		while(ob_get_level() > 0) {
			ob_end_clean();
		}
		
		/**
		 * Set the respons code to a 500 if headers are not sent.
		 */
		
		/**
		 * Require the template
		 */
		require 'views/errors/exception.php';
	}
}