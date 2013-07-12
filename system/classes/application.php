<?php
/**
 * LightPHP Framework
 * 
 * @description: simple and powerful PHP Framework
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
	 * Configurations loaded
	 * @var array
	 */
	protected $configs = array();

	/**
	 * Applciation Constructor
	 */
	public function __construct(){}

	/**
	 * Set the applications base path
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
	public function getConfiguration($key, $index = false)
	{
		/**
		 * Normalize the key value
		 * @var string
		 */
		$key = strtolower($key);

		/**
		 * Check to see if the ocnfiguration file has been loaded
		 */
		if(!array_key_exists($key, $this->configs))
		{
			/**
			 * Check to see if the configuration file exists.
			 */
			if(!$this->configurationExists($key))
			{
				throw new Exception("Configuration file (" . $key . ") does not exist", 1);
			}

			/**
			 * Require the configuration file
			 */
			require_once $this->getConfigurationsPath($key);

			/**
			 * Validate that we have a config variable
			 */
			if(!isset($config))
			{
				throw new Exception("Configuration does not contain a \$config array", 1);
			}

			/**
			 * Create a new configuration array within the config stack
			 */
			$this->configs[ $key ] = $config;
		}

		/**
		 * If we have a index then return that element
		 */
		if($index !== false)
		{
			return isset($this->configs[ $key ][ $index ]) ? $this->configs[ $key ][ $index ] : null;
		}

		return $this->configs[ $key ];
	}

	/**
	 * Checks to see if the specified configuration file exists
	 * @param  string $config
	 * @return boolean
	 */
	public function configurationExists($config)
	{
		return file_exists($this->getConfigurationsPath($config));
	}

	/**
	 * Returns the path to the configuration file
	 * @param  string $path
	 * @return string
	 */
	public function getConfigurationsPath($path = false)
	{
		return $this->applicationPath . ('/configs' . ($path  !== false ? '/' . $path . '.php' : ''));
	}

	/**
	 * Returns the path to a a controller file
	 * @param  string $controller
	 * @return string
	 */
	public function getControllerPath($controller)
	{
		return $this->applicationPath . '/controllers/' . $controller . '.php';
	}

	/**
	 * Checks wether a controller file exists
	 * @param  string $controller
	 * @return Boolean
	 */
	public function controllerExists($controller)
	{
		/**
		 * Check to see if the controller exists
		 */
		return file_exists($this->getControllerPath($controller));
	}

	/**
	 * Returns the path to the views folder
	 * @return string
	 */
	public function getViewsPath()
	{
		return $this->applicationPath . '/views/';
	}

	/**
	 * Returns the path to the views folder
	 * @return string
	 */
	public function getModelsPath()
	{
		return $this->applicationPath . '/models';
	}

	/**
	 * Check to see if a view file exists
	 * @param  string $view
	 * @return Boolean
	 */
	public function viewExists($view)
	{
		/**
		 * Check to see if the controller exists
		 */
		return file_exists($this->getViewsPath() . $view . '.php');
	}

	/**
	 * Check to see if a view file exists
	 * @param  string $view
	 * @return Boolean
	 */
	public function getBasePath()
	{
		return $this->applicationPath;
	}
}