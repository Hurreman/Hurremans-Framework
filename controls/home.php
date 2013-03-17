<?php
require_once('models/todolist.php');
require_once('models/user.php');

class home extends DBViewController
{
	public function init() {

		if($_SESSION['loggedin']) {
			$mL = new mTodoList();

			$this->view->todolists = $mL->search('userId = "' . $_SESSION['loggedin'] . '"');
		}

	}
}
?>