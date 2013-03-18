<?php
/**
 * Base controller
 */
class Controller {
	public function __construct() {
		$this->init();
	}
	
	public function execute() { }
	
	public function init() { }
}


/**
 * View controller that returns the contents of a view
 */
class ViewController extends Controller
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
}


/**
 * Simple Controller with database connection
 */
class DBController extends Controller
{
	protected $db;

	public function __construct() {
		$this->db = new cMySQL(array(DB_HOST,DB_DATABASE, DB_USER, DB_PWD));
		$this->init();
	}
}


/**
 * View controller with database connection
 */
class DBViewController extends ViewController
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
}
?>