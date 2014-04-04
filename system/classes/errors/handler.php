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
	 * Traceline Template
	 */
	protected $traceline = "#%s %s(%s): %s(%s)";

	/**
	 * Log template
	 */
	protected $log_template = "PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

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
	 * Write out an exception to the error_log
	 * @param  Exception $exception Exception in question
	 */
	protected function log_exception(Exception $exception)
	{
		/**
		 * Get the stack trace from the exveption
		 * @var Array
		 */
		$trace = $exception->getTrace();

		/**
		 * Sanitize the function values
		 */
		foreach ($trace as $key => $stackPoint)
		{
			/**
			 * I'm converting arguments to their type
			 * prevents passwords from ever getting logged as anything other than 'string'
			 */
			$trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
		}

		/**
		 * build your tracelines
		 * @var array
		 */
		$result = array();

		/**
		 * Itterate over the sta
		 * @var [type]
		 */
		foreach ($trace as $key => $stack)
		{
			$result[] = sprintf(
				$this->traceline,
				$key,
				empty($stack['file']) ? "??" : $stack['file'],
				empty($stack['line']) ? "??" : $stack['line'],
				empty($stack['function']) ? "?" : $stack['function'],
				implode(', ', $stack['args'])
			);
		}

		/**
		 * trace always ends with {main}
		 */
		$result[] = '#' . ++$key . ' {main}';

		/**
		 * write tracelines into main template
		 * @var String
		 */
		$report = sprintf($this->log_template, get_class($exception), $exception->getMessage(), $exception->getFile(),
			$exception->getLine(), implode("\n", $result), $exception->getFile(), $exception->getLine()
		);

		/**
		 * Send teh report ot the log file
		 */
		error_log($report);
	}

	/**
	 * Handles errors throw my the system or application
	 */
	public function handleError($errno, $errstr, $errfile, $errline)
	{
		$this->handleException(new Exception($errstr, $errno));
	}

	/**
	 * Handles exceptions throw my the system or application
	 */
	public function handleException(Exception $context)
	{
		/**
		 * Detect if this is a http exception
		 */
		$isHttpException = $context->getCode() >= 100 && $context->getCode() < 600;

		/**
		 * Log the exception if it's not a HTTP Exception
		 */
		!$isHttpException && $this->log_exception($context);

		/**
		 * If we are within the CLI, just output the error message
		 */
		if(IS_CLI)
		{
			/**
			 * As te exception is logged ot error_log above, we can just exit here as the caller
			 * has the trace on screen.
			 */
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
		if($isHttpException)
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
