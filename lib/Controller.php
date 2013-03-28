<?php
/**
 * Base controller
 */
class Controller {

	protected $permission = array('default' => 'public');

	public function __construct() {
		$this->init();
	}
	
	public function execute() { }
	
	public function init() { }

	public function getDefaultPermission() {
		if(is_array($this->permission) && isset($this->permission['default'])) {
			return $this->permission['default'];	
		}
		else {
			return false;
		}
		
	}

	public function getMethodPermission($method) {
		if(is_array($this->permission) && is_array($this->permission['methods'])) {
			$methods = $this->permission['methods'];
			if(isset($methods[$method])) {
				return $methods[$method];
			}
			else {
				return false;
			}
		}
	}
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