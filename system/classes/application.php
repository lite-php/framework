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
	 * Controller path
	 */
	private $controllerPath = "controllers";

	/**
	 * Is sub controller within the request
	 */
	private $isSubController = false;

	/**
	 * Applciation Constructor
	 */
	public function __construct()
	{
	}

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
		 * Bootstrap the application
		 */
		if(file_exists($this->getResourceLocation(null, "bootstrap", "php")))
		{
			require_once $this->getResourceLocation(null, "bootstrap", "php");
		}

		/**
		 * Fetch the requested route
		 * @var Route
		 */
		$route = Registry::get('Route');

		/**
		 * Security Check.
		 * If the route starts with _, dissalow it.
		 */
		if(substr($route->getMethod(), 0, 1) == '_')
		{
			throw new Exception("Not found!", 404);
		}

		/**
		 * See if we have a controller
		 */
		if(!$this->controllerExists($route->getController()))
		{
			if(!$this->controllerExists($route->getController() . "/" . $route->getMethod()))
			{
				/**
				 * Should send a 404 here
				 */
				throw new Exception("Not found!", 404);
			}

			/**
			 * Make as a subcontroller
			 */
			$this->isSubController = true;

			/**
			 * Update the controller path
			 */
			$this->setControllerPath($this->controllerPath . "/" . $route->getController());

			/**
			 * Update the controller
			 */
			$route->setController($route->getMethod());

			/**
			 * If we do not have a first paramter then default it to index.
			 * note the code below will pop this off and use it as the method
			 */
			if(!$route->getArgumentsAt(1))
			{
				$route->setArgument(1, "index");
			}

			/**
			 * Shift the arguments and set the method to the first arg.
			 */
			$route->setMethod($route->shiftArguments());
		}


		/**
		 * If we are able to load the vendor bootstrap then do that now.
		 */
		if(file_exists($this->getResourceLocation("vendor", "autoload", "php")))
		{
			require_once $this->getResourceLocation("vendor", "autoload", "php");
		}

		/**
		 * Trigger pre controller hook 
		 */
		Registry::get("Hooks")->do_action("system.pre_controller_load");

		/**
		 * Load the controller
		 */
		require_once $this->getControllerPath($route->getController());

		/**
		 * Trigger post controller hook 
		 */
		Registry::get("Hooks")->do_action("system.post_controller_load");

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
			throw new Exception("Not found!", 404);
		}

		/**
		 * Create a new reflection object
		 */
		$reflect = new ReflectionClass($controllerName);

		/**
		 * Create a new instance of the controller class
		 * @var Controller
		 */
		Registry::get("Hooks")->do_action("system.pre_controller_init");
		$controller = $reflect->newInstance();
		Registry::get("Hooks")->do_action("system.post_controller_init", $controller);

		/**
		 * Validate the class a valid class
		 */
		if(!$reflect->isUserDefined() || !$reflect->isInstantiable())
		{
			throw new Exception("Not found!", 404);
		}

		/**
		 * Check to see if the class has the right method
		 */
		if($reflect->hasMethod($route->getMethod()) === false)
		{
			/**
			 * Should throw a 404 here
			 */
			throw new Exception("Not found!", 404);
		}

		/**
		 * Get a reflection method class
		 * @var ReflectionMethod
		 */
		$method = $reflect->getMethod($route->getMethod());

		/**
		 * Validate the method
		 */
		if($method->isConstructor() || !$method->isPublic())
		{
			throw new Exception("Not found!", 404);
		}

		/**
		 * Loop the paramaters
		 */
		foreach ($method->getParameters() as $index => $parameter)
		{
			if($route->getArgumentsAt($index + 1) == null && !$parameter->isOptional())
			{
				/**
				 * Send back a 400 bad Request.
				 * > The request could not be understood by the server due to malformed syntax.
				 * > The client SHOULD NOT repeat the request without modifications.
				 */
				throw new Exception("Bad Request", 400);
			}
		}

		/**
		 * Run the method, we may implement a utility here
		 */
		Registry::get("Hooks")->do_action("system.pre_action_call");
		$result = call_user_func_array(array($controller, $route->getMethod()), $route->getArguments());
		Registry::get("Hooks")->do_action("system.post_action_call", $result);
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
			throw new Exception('Application does not exists', 500);
		}

		/**
		 * Validate that there is a controllers folder
		 */
		if(!is_dir($this->applicationPath . '/' . $this->controllerPath))
		{
			throw new Exception('Application does not contain a controllers folder', 500);
		}
	}

	/**
	 * Return true if the application is routing to a sub controller
	 * @return boolean
	 */
	public function isSubController()
	{
		return $this->isSubController;
	}

	/**
	 * Set the controller path if different to the default controllers/ path.
	 * @param String $path
	 */
	public function setControllerPath($path)
	{
		$this->controllerPath = $path;
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
		return $this->applicationPath . ($folder ? '/' . $folder : '') . ($file ? '/' . $file : '') . ($ext ? '.' . $ext : '');
	}

	/**
	 * Checks wether a controller file exists
	 * @param  string $controller
	 * @return Boolean
	 */
	public function controllerExists($controller)
	{
		return file_exists($this->getResourceLocation($this->controllerPath, $controller, 'php'));
	}

	/**
	 * Returns the path to a a controller file
	 * @param  string $controller
	 * @return string
	 */
	public function getControllerPath($controller, $folder = null)
	{
		if(!$folder)
		{
			return $this->getResourceLocation($this->controllerPath, $controller, 'php');
		}

		return $this->getResourceLocation($this->controllerPath, $folder . "/" . $controller, 'php');
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