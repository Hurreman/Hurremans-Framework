<?php
require_once('models/todoList_item.php');

class TodoList_item extends DBViewController {
	
	/**
	 * Create a new list item
	 */
	public function create()
	{
		// If we're coming from a form post
		if(Helper::validatePostArray(array('listId', 'name')))
		{
			$mL = new mTodoList_item();
			$modelData = $_POST;
			$mL->create($modelData);
			header('location: default.php?action=todolist&method=details&id=' . $_POST['listId']);
		}
		// If we don't have any form data, we render the create form
		else {
			if(isset($_GET['listId'])) {
				$mL = new mTodoList_item();
				$this->view = new cTemplate('views/genericCreateView.php');
				$this->view->model = $mL;
				$this->view->fk = $_GET['listId'];
				$this->view->frmAction = 'default.php?action=todolist_item&method=create';
				echo $this->render();
			}
			else {
				header('location: default.php');
			}
		}
	}
}
?>