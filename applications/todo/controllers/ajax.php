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
		 * Validate we have a XHR Request.
		 */
		if(!$this->input->isAjaxRequest())
		{
			/**
			 * Send a 404 and exit
			 */
			throw new Exception("Invalid request method.");
		}
	}

	/**
	 * Create / insert a new todo in the database
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

		/**
		 * If we was unable to create the row, send an error back to the client.
		 */
		if($id === false)
		{
			$this->output->sendJSON(array("error" => 'Unable to save to database', "msg" => $this->model->todos->errorInfo()));
			return;
		}

		/**
		 * Send back the new row we had created.
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