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
 * Application Class
 */
class Application
{
	/**
	 * Application bse path
	 * @var string
	 */
	private $applicationPath;

	/**
	 * Applciation Constructor
	 */
	public function __construct(){}

	/**
	 * Set the base path of the application that should be run.
	 * The path must contain atlest a controllers path.
	 * @param string $path
	 */
	public function setApplicationPath($path)
	{
		/**
		 * Make sure the directory exists
		 */
		if(!is_dir($path))
		{
			throw new Exception("Applications path does not exists: " . $path, 1);
		}

		/**
		 * Set the application path
		 */
		$this->applicationPath = $path;
	}

	/**
	 * Begin execution and routing of the application.
	 */
	public function run()
	{
		/**
		 * Validate the application.
		 */
		$this->validateApplication();

		/**
		 * Fetch the requested route
		 * @var Route
		 */
		$route = Registry::get('Route');

		/**
		 * See if we have a controller
		 */
		if(!$this->controllerExists($route->getController()))
		{
			/**
			 * Should send a 404 here
			 */
			throw new Exception("Controller (" . $route->getController() . ") does not exists");
		}

		/**
		 * If we are able to load the vendor bootstrap then do that now.
		 */
		if(file_exists($this->getResourceLocation("vendor", "autoload", "php"))) {
			require_once $this->getResourceLocation("vendor", "autoload", "php");
		}

		/**
		 * Load the controller
		 */
		require_once $this->getControllerPath($route->getController());

		/**
		 * Containes the fill controller class name
		 * @var string
		 */
		$controllerName = ucfirst(strtolower($route->getController())) . '_Controller';

		/**
		 * Make sure the class now exists
		 */
		if(!class_exists($controllerName))
		{
			throw new Exception("Controller (" . $route->getController() . ") is malformed");
		}

		/**
		 * Instnatiate the controller
		 * @var object
		 */
		$controller = new $controllerName();

		/**
		 * Check to see if the class has the right method
		 */
		if(!method_exists($controller, $route->getMethod()))
		{
			/**
			 * Should throw a 404 here
			 */
			throw new Exception("Controller (" . $route->getController() . "::" . $route->getMethod() . ") does not exists");
		}

		/**
		 * Run the method
		 */
		call_user_func_array(array($controller, $route->getMethod()), $route->getArguments());
	}

	/**
	 * Validate the application path exists
	 * @throws Exception if the application is currupt
	 */
	public function validateApplication()
	{
		/**
		 * Validate that the application path exists
		 */
		if(!is_dir($this->applicationPath))
		{
			throw new Exception('Application does not exists', 1);
		}

		/**
		 * Validate that there is a controllers folder
		 */
		if(!is_dir($this->applicationPath . '/controllers'))
		{
			throw new Exception('Application does not contain a controllers folder');
		}
	}

	/**
	 * Returns a configuration object for the application
	 * @param  string  $key
	 * @param  boolean $index
	 * @return object
	 * @throws Exception If the configuration file does nto exists or is currupt
	 */
	public function getConfiguration($key)
	{
		return Registry::get('ConfigLoader')->get($key);
	}

	/**
	 * Return a path to a folder within the application area.
	 */
	public function getResourceLocation($folder = false, $file = false, $ext = false)
	{
		/**
		 * Value to return defaults to the application root
		 */
		return $this->applicationPath . ($folder ? '/' . $folder : '') . ($file ? '/' . $file : '') . ($ext ? '.' . $ext : '');
	}

	/**
	 * Checks wether a controller file exists
	 * @param  string $controller
	 * @return Boolean
	 */
	public function controllerExists($controller)
	{
		return file_exists($this->getResourceLocation('controllers', $controller, 'php'));
	}

	/**
	 * Returns the path to a a controller file
	 * @param  string $controller
	 * @return string
	 */
	public function getControllerPath($controller)
	{
		return $this->getResourceLocation('controllers', $controller, 'php');
	}

	/**
	 * Check to see if a view file exists
	 * @param  string $view
	 * @return Boolean
	 */
	public function viewExists($view)
	{
		return file_exists($this->getResourceLocation('views', $view, 'php'));
	}

	/**
	 * Check to see if a view file exists
	 * @param  string $view
	 * @return Boolean
	 */
	public function getApplicationPath()
	{
		return $this->getResourceLocation();
	}
}