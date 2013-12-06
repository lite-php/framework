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
	 * @var Resource
	 */
	protected $stdin;

	/**
	 * Parser Object
	 * @var CLIParser
	 */
	protected $parser;

	/**
	 * string values that can be assumed as a true value
	 * @var Array
	 */
	protected $true_like_values = array("yes", "y", "ok", "true");

	/**
	 * Open streams and link sub-objects
	 */
	public function __construct()
	{
		/**
		 * Get the stdin
		 */
		$this->stdin = fopen('php://stdin', 'r');

		/**
		 * Set the parser object
		 */
		$this->parser = Registry::get('CLIParser');
	}

	/**
	 * [Return a boolean from a given set of positive values
	 * @param  string $value value from input
	 * @return boolean       converted boolean
	 */
	protected function _bool($value)
	{
		return in_array(strtolower($value), $this->true_like_values);
	}

	/**
	 * Promt a question and return a misc input
	 * @param  string $msg question to be asked
	 * @return string      answer
	 */
	public function prompt($msg)
	{
		/**
		 * Show the message
		 */
		$this->out($msg . ": ", false);

		/**
		 * Return the input
		 */
		return $this->read();
	}

	/**
	 * Ask a queston, alias for {@link prompt}
	 * @param  string $msg question to be asked
	 * @return string      answer
	 */
	public function ask($msg)
	{
		return $this->prompt($msg);
	}

	/**
	 * Ask a uestion and get a yes/no decision
	 * @param  string $message question to ask
	 * @return boolean         answer casted to a boolean
	 */
	public function confirm($message)
	{
		return $this->_bool($this->prompt($message));
	}

	/**
	 * Display a list of options with a question
	 * @param  string 			$question Question to ask.
	 * @param  array  			$options  List of options to show.
	 * @param  int|string|null 	$default  Default option if enter is pressed.
	 * @return int|string 		Answer from the input
	 */
	public function choose($question, $options, $default = null)
	{
		/**
		 * List out the options
		 */
		foreach ($options as $index => $description)
		{
			$this->out(sprintf("  %.12s) %-30.30s", $index, $description));
		}

		/**
		 * Ask the question
		 */
		$this->out($question . ": ", false);

		/**
		 * Get the asnwer
		 */
		$answer = $this->read();

		/**
		 * Validate the answer is acceptible
		 */
		if(array_key_exists($answer, $options))
		{
			return $answer;
		}

		/**
		 * Check for the defualt 
		 */
		if($default && $answer == "")
		{
			return $default;
		}

		/**
		 * aask the question again, recursivly
		 */
		return $this->choose($question, $options);
	}

	/**
	 * A utility method to return a postional argument of flag
	 */
	public function get($key, $default = null)
	{
		/**
		 * Check the arguments first
		 */
		if($this->parser->getArgument($key) !== null)
		{
			return $this->parser->getArgument($key);
		}

		/**
		 * Check the flags
		 */
		if($this->parser->getFlag($key) !== null)
		{
			return $this->parser->getFlag($key);
		}

		/**
		 * Return the defualt
		 */
		return $default;
	}

	public function flag($key)
	{
		/**
		 * Return the flag or false
		 */
		return $this->parser->getFlag($key);
	}

	/**
	 * Write dat to the output class
	 * @param  string  $data    data to write
	 * @param  boolean $newline end with a new line
	 * @return void
	 */
	protected function out($data, $newline = true)
	{
		Registry::get("Output")->send($data, $newline);
	}

	/**
	 * Get the user input from stdin
	 * @return string trimmed value from stdin
	 */
	public function read()
	{
		return trim(fgets($this->stdin));
	}
}