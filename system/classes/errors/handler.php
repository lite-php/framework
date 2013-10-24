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
	public function handleException(Exception $context)
	{
		/**
		 * If we are within the CLI, just output the error message
		 */
		if(IS_CLI)
		{
			//$output = Registry::get('Output');
			error_log("Error: Message: " . $context->getMessage());
			error_log("Error: File:    " . $context->getFile());
			error_log("Error: Line:    " . $context->getLine());
			//$output->send("Error: Message: " . $context->getMessage());
			//$output->send("Error: File:    " . $context->getFile());
			//$output->send("Error: Line:    " . $context->getLine());

			foreach ($context->getTrace() as $trace)
			{
				print_r($trace);
			}

			return;
		}
		
		/**
		 * Attaempt tp clear out any buffers
		 */
		while(ob_get_level() > 0)
		{
			ob_end_clean();
		}

		/**
		 * Header status code
		 */
		$status = 500;

		/**
		 * If the exception code is 404, we treat that as a not found.
		 */
		if($context->getCode() >= 100 && $context->getCode() < 600)
		{
			$status = $context->getCode();
		}

		/**
		 * Set and flush the headers
		 */
		Registry::get("Output")->setStatus($status);
		Registry::get("Output")->sendHeaders();

		/**
		 * Look in the errors folder for a $status.php, use this
		 */
		$_specific = Registry::get('Application')->getResourceLocation('errors', $status, 'php');
		$_generic  = Registry::get('Application')->getResourceLocation('errors', 'generic', 'php');

		/**
		 * If the page exists, include it.
		 */
		if(file_exists($_specific))
		{
			require $_specific;
			exit(0);
		}

		/**
		 * If the generic page exists, include it.
		 */
		if(file_exists($_generic))
		{
			require $_generic;
			exit(0);
		}
		
		/**
		 * Require the template
		 */
		require 'views/errors/exception.php';
	}
}