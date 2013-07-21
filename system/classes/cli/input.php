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

class CLIInput
{
	/**
	 * Input stream
	 */
	protected $stdin;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		/**
		 * Get the stdin
		 */
		$this->stdin = fopen('php://stdin', 'r');
	}

	/**
	 * Asks the user a question
	 * @return string answer
	 */
	public function ask($question, $allowed_responses = array())
	{
		/**
		 * Map the possible answers
		 */
		for($i = 0; $i < count($allowed_responses); $i++)
		{
			Registry::get('Output')->send("[" . $i . "] " . $allowed_responses[$i]);
		}

		/**
		 * Send the question to the output class
		 */
		Registry::get('Output')->send($question . ": ", false);

		/**
		 * Read teh answer
		 */
		$answer = $this->read();

		/**
		 * If we have an number and it's within the range of the answers
		 */
		if(count($allowed_responses) > 0 && (is_numeric($answer) && isset($allowed_responses[$answer])))
		{
			/**
			 * Return the number
			 */
			return $allowed_responses[$answer];
		}

		if(count($allowed_responses) > 0 && !isset($allowed_responses[$answer]))
		{
			/**
			 * We should ask the question again as thats an invalid response
			 */
			Registry::get('Output')->error("Inavlid answer, please choose from the following options");
			return $this->ask($question, $allowed_responses);
		}

		/**
		 * Return teh answer as its just a requested string
		 */
		return $answer;
	}

	public function read()
	{
		return fgets($this->stdin);
	}
}