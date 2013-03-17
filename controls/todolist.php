<?php
require_once('models/todolist.php');

class TodoList extends DBViewController
{
	public function create()
	{
		if(Helper::validatePostArray(array('userId', 'name')))
		{
			$mL = new mTodoList();
			$modelData = $_POST;
			$mL->create($modelData);
			header('location: default.php');
		}
		else
		{
			$mL = new mTodoList();
			$this->view = new cTemplate('views/genericCreateView.php');
			$this->view->model = $mL;
			$this->view->fk = $_SESSION['loggedin'];
			$this->view->frmAction = 'default.php?action=todolist&method=create';
			echo $this->render();
		}
	}

	public function details() {
		if(isset($_GET['id'])) {

		}
		else {
			
		}
	}
}
?>