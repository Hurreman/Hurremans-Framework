<?php
/**
 * View controller that returns the contents of a view
 */
class ViewController
{
	protected $tpl;
	protected $view;
	
	public function __construct() {
		$this->tpl = new cTemplate('views/master.php');
		$this->view = new cTemplate('views/' . get_class($this) . '.php');
		$this->init();
	}
	
	public function render() {
		
		$this->tpl->view = $this->view->Render();
		return $this->tpl->Render();
	}
	
	public function execute() {
		echo $this->render();
	}
	
	public function init() {
		
	}
}


/**
 * Action controller that simply... DOES something without returning a view
 */
class ActionController
{	
	public function __construct() {
		$this->init();
	}
	
	public function execute() {
	}
	
	public function init() {
		
	}
}


/**
 * Simple Controller with database connection
 */
class DBController
{
	
	protected $db;

	public function __construct() {
		$this->db = new cMySQL(array(DB_HOST,DB_DATABASE, DB_USER, DB_PWD));
		$this->init();
	}
	
	public function execute() {
	}
	
	public function init() {
		
	}
}


/**
 * View controller with database connection
 */
class DBViewController
{
	protected $tpl;
	protected $view;
	protected $db;
	
	public function __construct() {
		$this->tpl = new cTemplate('views/master.php');
		$this->view = new cTemplate('views/' . get_class($this) . '.php');
		$this->db = new cMySQL(array(DB_HOST,DB_DATABASE, DB_USER, DB_PWD));
		$this->init();
	}
	
	public function render() {
		$this->tpl->view = $this->view->Render();
		return $this->tpl->Render();
	}
	
	public function execute() {
		echo $this->render();
	}
	
	public function init() {
	}
}
?>