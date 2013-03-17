<div id="TodoListDetails">
	<h2>List: <?php echo $todolist->get('name'); ?></h2>
	<a href="default.php">Back to lists</a>
	<ul>
		<?php
		foreach($todoList_items as $item) {
			echo '<li>' . $item->get('name') . '<a href="default.php?action=todolist_item&method=delete&id=' . $item->get('id') . '">x</a></li>';
		}
		?>
	</ul>
	<a href="default.php?action=todolist_item&method=create&listId=<?php echo $todolist->get('id'); ?>">Add new item</a>
</div>
<a hreF="default.php?action=todolist&method=delete&id=<?php echo $todolist->get('id'); ?>">Delete list</a>