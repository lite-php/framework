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

class CLIOutput
{
	/**
	 * New line 
	 */
	protected $newline = "\n";

	/**
	 * The stdout stream
	 */
	protected $stdout;

	/**
	 * The error stream
	 */
	protected $stderr;

	/**
	 * Defualt prompt
	 */
	protected $prompt = "";

	/**
	 * Constructor
	 */
	public function __construct()
	{
		/**
		 * Detect what new line should be used
		 */
		$this->newline = IS_WIN ? "\r\n" : "\n";

		/**
		 * Connect to the stream
		 */
		$this->stdout = fopen('php://stdout', 'w');

		/**
		 * Connect to the stream
		 */
		$this->stderr = fopen('php://stderr', 'w');

		/**
		 * Set the default prompt
		 */
		$this->setPrompt(CURRENT_USER . "@" . VERSION . "> ");
	}

	/**
	 * Set the prompt
	 */
	public function setPrompt($prompt)
	{
		$this->prompt = $prompt;
	}

	/**
	 * Send a message to the console
	 */
	public function send($data, $newline = true)
	{
		$this->write($this->stdout, $this->prompt . $data . ($newline ? $this->newline : ''));
	}

	public function error($data, $newline = true)
	{
		$this->write($this->stderr, ($newline ? $this->prompt : '') . $data . ($newline ? $this->newline : ''));
	}

	/**
	 * Clear the console
	 */
	public function clear()
	{
		if(IS_WIN)
		{
			system('@echo off');
		}

		system(IS_WIN ? 'cls' : 'clear');
	}

	public function write($stream, $raw)
	{
		/**
		 * Write the payload
		 */
		fwrite($stream, $raw);
	}
}