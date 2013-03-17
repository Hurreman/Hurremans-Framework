<?php
require_once('models/todolist.php');
require_once('models/todolist_item.php');

class TodoList extends DBViewController
{
	/**
	 * Create a new list
	 */
	public function create()
	{
		// If we're coming from a form post
		if(Helper::validatePostArray(array('userId', 'name')))
		{
			$mL = new mTodoList();
			$modelData = $_POST;
			$mL->create($modelData);
			header('location: default.php');
		}
		// If we don't have any form data, we render the create form
		else {
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
			$mL = new mTodoList($_GET['id']);
			$this->view = new cTemplate('views/todolistDetails.php');
			$this->view->todolist = $mL;
			$this->view->todoList_items = mTodoList_item::search('listId=' . $_GET['id']);
			echo $this->render();
		}
		else {
			header('location: default.php');
		}
	}

	public function delete() {
		if(isset($_GET['id'])) {
			$mL = new mTodolist();
			$mLI = new mTodolist_item();
			$mLI->deleteItemsFromList($_GET['id']);
			$mL->delete($_GET['id']);
			header('location: default.php');
		}
	}
}
?>