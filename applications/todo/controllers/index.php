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
		$this->view->scripts = array("jquery.min", "bootstrap.min", 'todo');
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

	public function mailtest()
	{
		/**
		 * Load the simple mail library
		 */
		$mail = $this->library->simplemail;

		/**
		 * For the test email we send the todo.sqlite database
		 */
		$attachment = $this->application->getResourceLocation(null, 'todo', 'sqlite');

		/**
		 * Configure a test email
		 */
		$sent = $mail
			->setTo('robert@localhost', 'Robert Pitt')
			->setSubject('This is a sample email from simple mail')
			->setFrom('robert@localhost', 'localhost')
			->addMailHeader('Reply-To', 'robert@localhost', 'localhost')
			->addGenericHeader('X-Mailer', 'PHP/' . phpversion())
			->setMessage('<strong>This is a sample email from simple mail</strong>')
			->addAttachment($attachment)
			->setWrap(100)
			->send();

		/**
		 * Validate that the email was sent
		 */
		echo $sent ? "Email sent" : "Unable to send email.";
		echo $mail->debug();
	}

	public function recaptcha()
	{
		/**
		 * Load the library
		 */
		$recaptcha = $this->library->recaptcha;

		echo $recaptcha->generate();
	}
}