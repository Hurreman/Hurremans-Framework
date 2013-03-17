<?php
/**
 * Template class to separate presentation from logic
 * @author Fredrik Karlsson
 * @version 0.1.0
 */
class cTemplate
{
	/**
	 * Array holding all our template vars
	 * @access protected
	 * @var array
	 */
	protected $vars = array();
	
	/**
	 * Template filename
	 * @access protected
	 * @var string
	 */
	protected $file;
	
	/**
	 * @access public
	 */
	public function __construct($file = null)
	{
		$this->file = $file;
	}
	
	/**
	 * Sets a variable for access from the template
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key,$value)
	{
		$this->vars[$key] = $value;
	}
	
	/** 
	 * Using $this->set($key,$value) can be tedious, so a __set() version comes in handy
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value)  {
		$this->set($key, $value);
	}
	
	/**
	 * Parses and renders the template
	 * @access public
	 * @param string $file
	 * @return string Outputs XHTML/HTML/Text
	 */
	public function render($file = null)
	{
		// If no filename was specified, fetch from $this->file
		if(!$file) {
			$file = $this->file;
		}
		// Extract all variables from the $this->vars array.
		// e.g $this->vars['pageTitle'] becomes $pageTitle, for use from within the template
		extract($this->vars);
        ob_start();
        include($file);
        $contents = ob_get_contents();
        ob_end_clean();
		
        return $contents;
	}	
}
?>