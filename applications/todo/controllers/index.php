<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
class Index_Controller extends Controller
{
	/**
	 * Index Controller
	 */
	public function __construct()
	{
		/**
		 * Add the stylesheet list to the view
		 */
		$this->view->stylesheets = array("bootstrap.min");

		/**
		 * Set the javascripts for the view
		 */
		$this->view->scripts = array("jquery.min", "bootstrap.min", 'app');
	}

	/**
	 * Index Method
	 */
	public function index()
	{
		/**
		 * Fetch the todos from the datastore
		 */
		$todos = $this->model->todos->all('todos');

		/**
		 * Set the todos to the view
		 */
		$this->view->todos = $todos;

		/**
		 * Render the output
		 */
		$this->view->render('index');
	}

	public function library()
	{
	}
}