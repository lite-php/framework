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
	 * Available background colors
	 */
	protected $foreground_colors = array(
		'black'			=> '0;30',
		'dark_gray'		=> '1;30',
		'blue'			=> '0;34',
		'dark_blue'		=> '1;34',
		'light_blue'	=> '1;34',
		'green'			=> '0;32',
		'light_green'	=> '1;32',
		'cyan'			=> '0;36',
		'light_cyan'	=> '1;36',
		'red'			=> '0;31',
		'light_red'		=> '1;31',
		'purple'		=> '0;35',
		'light_purple'	=> '1;35',
		'light_yellow'	=> '0;33',
		'yellow'		=> '1;33',
		'light_gray'	=> '0;37',
		'white'			=> '1;37',
	);

	/**
	 * Available background colros
	 */
	protected $background_colors = array(
		'black'			=> '40',
		'red'			=> '41',
		'green'			=> '42',
		'yellow'		=> '43',
		'blue'			=> '44',
		'magenta'		=> '45',
		'cyan'			=> '46',
		'light_gray'	=> '47',
	);

	/**
	 * Constructor
	 */
	public function __construct()
	{
		/**
		 * Detect what new line should be used
		 */
		$this->newline = IS_WINDOWS ? "\r\n" : "\n";

		/**
		 * Connect to the stream
		 */
		$this->stdout = fopen('php://stdout', 'w');

		/**
		 * Connect to the stream
		 */
		$this->stderr = fopen('php://stderr', 'w');
	}

	/**
	 * Set the prompt
	 */
	public function setPrompt($prompt)
	{
		$this->prompt = $prompt;
	}

	/**
	 * Color some text for terminal display
	 */
	public function _color($text, $foreground = null, $background = null)
	{
		/**
		 * Only color text if we are in linux and have ANSI available
		 */
		if(IS_WINDOWS)
		{
			return $text;
		}

		$string = "";

		/**
		 * Validate the color is available
		 */
		if($foreground && array_key_exists($foreground, $this->foreground_colors))
		{
			$string = "\033[".$this->foreground_colors[$foreground]."m";
		}

		/**
		 * Validate the color is available
		 */
		if($background && array_key_exists($background, $this->background_colors))
		{
			$string .= "\033[".$this->background_colors[$background]."m";
		}

		$string .= $text . ($string != "" ? "\033[0m" : "");
		return $string;
	}
	
	/**
	 * Send a message to the console
	 */
	public function send($data, $newline = true, $foreground = null, $background = null)
	{
		if($foreground)
		{
			$data = $this->_color($data, $foreground, $background);
		}

		/**
		 * If we have a color, process the data prior.
		 */
		$this->write($this->stdout, $this->prompt . $data . ($newline ? $this->newline : ''));
	}

	public function error($data, $newline = true)
	{
		$data = $this->_color($data, "light_red");
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