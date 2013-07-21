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
class Install_Controller extends Controller
{
	/**
	 * Index Controller
	 */
	public function __construct()
	{
		/**
		 * Clear the command line
		 */
		$this->output->clear();

		/**
		 * Set the promt
		 */
		$this->output->setPrompt(CURRENT_USER . "@" . VERSION . "> ");
	}

	/**
	 * Index Method
	 */
	public function index()
	{
		/**
		 * Show the help screen
		 */
		$this->help();
	}

	public function help()
	{

	}

	/**
	 * This allows the user to install a submodule as a library
	 */
	public function library($arguments = array())
	{
		/**
		 * If we do not have any arguments show an error
		 */
		if(count($arguments) == 0)
		{
			$this->output->error("No installable module specified.");
			return;
		}

		/**
		 * The first argument after is the installable module
		 */
		$name = $arguments[0];

		/**
		 * Begin the installation of the module
		 */
		$this->output->send(sprintf("Searching for: " . $name));

		sleep(2);
	}
}