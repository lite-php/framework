<?php
/**
 * LightPHP Framework
 *
 * @description: simple and powerful PHP Framework
 */
class Index_Controller extends Controller
{
	public function __construct()
	{
		/**
		 * Here we can initialize
		 */
	}

	public function index()
	{
		/**
		 * Send some content
		 */
		$this->output->send("Hello World");
	}

	public function json()
	{
		$payload = array("key" => "value");

		/**
		 * Send it via sendJSON
		 */
		$this->output->sendJSON($payload);
	}

	public function template()
	{
		$this->view->render("index", array("title" => "Hello World"));
	}
}