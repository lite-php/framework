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
		 * Set the dat from the model to the view.
		 */
		$this->view->todos = $this->model->todos->all('todos');

		/**
		 * Render the output
		 */
		$this->view->render('index');
	}

	public function library()
	{
		/**
		 * Get the session variable
		 */
		$session  = $this->library->session;

		/**
		 * Set avalue then get the value from the session
		 */
		$session->set('test', 'test');

		/**
		 * Set avalue then get the value from the session
		 */
		echo $session->get('test');
	}
}