<div>

	<?php
	if(!$_SESSION['loggedin']) {
		?>
		<form method="POST" action="default.php?action=user&method=login">
			<h2>You need to log in to view and manage your lists!</h2>
			<label>
				<input type="text" placeholder="Your username" id="username" name="username" />
				<input type="password" placeholder="Your password" id="password" name="password" />
				<input type="submit" value="Login" />
			</label>
		</form>
		<p>Not registered yet? <a href="default.php?action=user&method=create">Sign up now!</a></p>
		<?php
	}
	else {
		?>
		<h1>My lists</h1>
		<dl>
			<?php
			foreach($todolists as $todolist) {
				echo '<dt><a href="default.php?action=todolist&method=details&id='. $todolist->get('id') .'">' . $todolist->get('name') . '</a></dt><dd>' . $todolist->get('description') . '</dd>';
			}
			?>
		</dl>
		<a href="default.php?action=todolist&method=create">Create new list</a>
		<?php
	}
	?>

</div>

<hr/>
<a href="default.php?action=user&method=logout">Logout</a>