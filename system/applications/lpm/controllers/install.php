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
	 * Git Reference checkers, this allows us to validate as well as detect tag names
	 * @param string
	 */
	protected $reference_regex = '/[a-z0-9\-\_\/\.]/i';

	/**
	 * Index Controller
	 */
	public function __construct()
	{
		/**
		 * Load the configuration for lpm
		 */
		$this->configuration = $this->config->lpm;

		/**
		 * Load the github modal
		 */
		$this->github = $this->model->github;

		/**
		 * Clear the command line
		 */
		$this->output->clear();
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
		$name = $arguments[0] . '-library';

		/**
		 * Begin the installation of the module
		 */
		$this->output->send("Searching for: " . $name);

		/**
		 * Check to see if the repository exists
		 */
		if($this->github->exists('lite-php', $name) === false)
		{
			$this->output->error("Unable to locate: " . $name);
			return;
		}

		/**
		 * Now we need to get the data for this repo.
		 */
		$this->output->send("Library found, pulling latest info");

		/**
		 * Fetch
		 * @var object
		 */
		$repo = $this->github->getInfo('lite-php', $name);

		/**
		 * Listout what we are pulling.
		 */
		$this->output->send("Name        : " . $repo->full_name);
		$this->output->send("Repository  : " . $repo->html_url);
		$this->output->send("Description : " . $repo->description);
		$this->output->send("Branch Name : " . $repo->default_branch);
		$this->output->send("Last Updated: " . $repo->pushed_at);

		/**
		 * Get the branches
		 */
		$branches = $this->model->github->getBranches('lite-php', $name);

		/**
		 * Map the array
		 */
		$available = array_map(function($branch){ return $branch->name; }, $branches);

		/**
		 * If we have a stable branch, use that otherwise use the master
		 */
		$branch = $this->input->ask("What branch would you like to use", $available);

		/**
		 * Start downloading the branch to the right area
		 */
		$this->output->send("Downloading (" . $branch . ") to libraries");
	}
}