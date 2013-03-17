<?php
/**
 * Class: mTodoList
 * Author: Fredrik Karlsson (fredrik@fkinnovation.se)
 * Created: 2013-03-17
 * Desc: Todo List
 */
class mTodoList extends Model
{
    protected $id;
    protected $name;
    protected $userId;
    protected $description;

    protected $tableName = 'todolists';

    protected $createCols = array('name' => 'text', 'description' => 'text', 'userId' => 'fk');
}
?>