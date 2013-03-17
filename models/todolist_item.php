<?php
/**
 * Class: mTodoList
 * Author: Fredrik Karlsson (fredrik@fkinnovation.se)
 * Created: 2013-03-17
 * Desc: Todo List Item
 */
class mTodolist_item extends Model
{
	protected $id;
    protected $name;
    protected $listId;
    protected $status;

    protected $tableName = 'todolist_items';

    protected $createCols = array('name' => 'text', 'listId' => 'fk');

    public function deleteItemsFromList($listId) {
    	$query = $this->db->createQuery('DELETE FROM ? WHERE listId = ?');
    	$query->setParameter(1, $this->tableName, true);
    	$query->setParameter(2, $listId);
    	if($query->execute()) {
    		return true;
    	}
    	else {
    		return false;
    	}
    }
}
?>