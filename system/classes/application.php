<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
class Application
{
	/**
	 * @type string Applications Path
	 */
	public $applicationPath;

	/**
	 * @cosntructor
	 */
	public function __cosntruct()
	{

	}

	/**
	 * Set the applications base path
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
	 * Run a specific application
	 */
	public function run()
	{
		/**
		 * Validate the application.
		 */
		$this->validateApplication();

		/**
		 * Fetch the requested route
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
		 * Load the controller
		 */
		require_once $this->getControllerPath($route->getController());

		/**
		 * Cosntruct the controller name
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

	public function getControllerPath($controller)
	{
		return $this->applicationPath . '/controllers/' . $controller . '.php';
	}

	public function controllerExists($controller)
	{
		/**
		 * Check to see if the controller exists
		 */
		return file_exists($this->getControllerPath($controller));
	}


	public function getViewPath()
	{
		return $this->applicationPath . '/views/';
	}

	public function viewExists($view)
	{
		/**
		 * Check to see if the controller exists
		 */
		return file_exists($this->getViewPath() . $view . '.php');
	}
}