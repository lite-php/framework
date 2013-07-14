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
		trigger_error("sample");
	}
}