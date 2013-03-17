<?php
/**
 * Generic Create View
 * This view is used to quickly create a page for inserting new data into a model.
 * It goes through all fields in a model and creates input fields accordingly.
 * The view can also be used to edit an item, in which case an "id" is stored as a hidden field.
 */
?>
<form method="post" action="<?php echo $frmAction; ?>" class="genericCreateForm">
	
	<?php
	if(isset($id)) {
		echo '<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />';
	}
	?>
	
	<ul>
		
	<?php
	/**
	 * Loop through all "createCols" in a model and create input fields of the correct type.
	 */
	foreach($model->getCreateCols() as $field => $type)
	{
		// If the field is a Foreign Key, we create a hidden input to store its value
		if($type == 'fk') { ?>
			<li>
				<input type="hidden" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="<?php echo $fk; ?>"/>
				Foreign key: <?php echo $fk; ?>
			</li>
		<?php
		}
		// Normal text input
		else {
		?>
			<li>
				<label>
					<?php echo $field; ?>
					<input type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>" value="<?php echo $model->get($field);?>"/>
				</label>
			</li>
		<?php
		}
	}
	?>
	
	</ul>
	
	<input type="submit" value="Save" />
	
	<?php
	
	if(isset($id)) {
	?>
		<a class="del" href="default.php?action=adress&method=delete&id=<?php echo $id; ?>&fk=<?php echo $fk; ?>">Delete</a>
	<?php
	}
	?>
	
</form>