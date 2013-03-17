<?php
require_once('models/user.php');
class User extends DBViewController
{
	/**
	 * Show user details
	 */
	public function details()
	{
		if(isset($_GET['id']))
		{
			$mU = new mUser($_GET['id']);
			$this->view = new cTemplate('views/userDetails.php');
			$this->view->user = $mU;
			echo $this->render();
		}
		else
		{
			header('location: default.php?action=user&method=showlist');
		}
	}
	
	/**
	 * List all users
	 */
	public function showList()
	{
		$this->view = new cTemplate('views/userList.php');
		$this->view->users = mUser::fetchAll();
		echo $this->render();
	}

	/**
	 * Create new user
	 */
	public function create()
	{
		/**
		 * Check for _POST and validate required fields.
		 * If one of the fields aren't set, show the form instead of calling create.
		 */
		if(Helper::validatePostArray(array('username', 'password')))
		{
			$mU = new mUser();
			$modelData = $_POST;
			$modelData['password'] = md5($modelData['password']);
			$mU->create($modelData);
			header('location: default.php');
		}
		else
		{
			$mU = new mUser();
			$this->view = new cTemplate('views/genericCreateView.php');
			$this->view->model = $mU;
			$this->view->frmAction = 'default.php?action=user&method=create';
			echo $this->render();
		}
	}
	
	/**
	 * Edit User
	 */
	public function edit()
	{
		if(Helper::validatePostArray(array('id', 'username', 'password')))
		{
			$modelData = $_POST;
			$mU = new mUser();
			$mU->update($_POST['id'], $modelData);
			header('location: default.php?action=user&method=showList');
		}
		else
		{
			if(isset($_GET['id']))
			{
				$mU = new mUser($_GET['id']);
				
				$this->view = new cTemplate('views/genericCreateView.php');
				$this->view->model = $mU;
				$this->view->frmAction = 'default.php?action=user&method=edit';
				$this->view->id = $_GET['id'];
				echo $this->render();
				
			}
			else
			{
				header('location: default.php?action=user&method=showList');
			}
		}
	}
	
	/**
	 * Delete user
	 */
	public function delete()
	{
		if(isset($_GET['id']))
		{
			$mU = new mUser();
			$mU->delete($_GET['id']);
		}
		header('location: default.php?action=user&method=showList');
	}

	/**
	 * Login as a user
	 */
	public function login() {
		if(isset($_POST['username']) && isset($_POST['password'])) {
			$mU = new mUser();
			$userId = $mU->login($_POST['username'], $_POST['password']);
			if($userId) {
				$_SESSION['loggedin'] = $userId;
			}
		}
		header('location: default.php');
	}

	/**
	 * Logout a user
	 */
	public function logout() {
		unset($_SESSION['loggedin']);
		header('location: default.php');
	}
}
?>