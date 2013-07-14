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
class Ajax_Controller extends Controller
{
	/**
	 * Ajax Controller
	 */
	public function __construct()
	{
		/**
		 * Validate we have a XHR Request with header.
		 */
		if(!$this->input->isAjax())
		{
			/**
			 * Send a 404 and exit
			 */
		}
	}

	/**
	 * Create / isnert a new todo in the database
	 */
	public function create()
	{
		/**
		 * Get the required params
		 */
		$description = $this->input->post('description');

		/**
		 * Make sure that we have a description variable
		 */
		if(!$description || empty($description))
		{
			$this->output->sendJSON(array("error" => 'No description given'));
			return;
		}

		/**
		 * Let's add this to the model
		 */
		$id = $this->model->todos->create($description, 0);

		if($id === false)
		{
			$this->output->sendJSON(array("error" => 'Unable to save to database', "msg" => $this->model->todos->errorInfo()));
			return;
		}

		/**
		 * Successful insert, send back the ID
		 */
		$this->output->sendJSON(array(
			"id" => $id,
			"description" => $description,
			"completed" => 0
		));
	}

	/**
	 * Read a specific todo from the database
	 */
	public function read($id)
	{
	}

	/**
	 * Updates a todo item from the database
	 */
	public function update($id)
	{
	}

	/**
	 * Removes a todo item from the database
	 */
	public function delete($id)
	{
	}

	/**
	 * Response with a JSON Object containing all todos within the database
	 */
	public function search() {
	}
}