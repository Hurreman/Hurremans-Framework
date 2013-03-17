<?php
/**
 * Base Model
 *
 * @author Fredrik Karlsson <fredrik@fkinnovation.se>
 */
class Model
{
	protected $db;
	protected $tableName;		// Models need to set the $tableName property
	protected $idField = 'id';	// Sets the default idField to "id"
	protected $createCols = array();
	
	/**
	 * Model Class Constructor
	 * 
	 * @param variable $result Either an array-representation of the model data, or the id of the model to retrieve from db
	 */
	public function __construct($result = false)
	{
		$this->db = new cMySQL(array(DB_HOST, DB_DATABASE, DB_USER, DB_PWD));
		
		if(is_array($result)) {
			$this->assignFromResult($result);
		}
		else if(is_numeric($result)) {
			$this->assignFromDb($result);
		}
	}

	/**
	 * Magic Get
	 */
	public function get($field) {
		if(isset($this->$field)) {
			return $this->$field;
		}
		else {
			return false;
		}
	}
	
	
	/**
	 * Fetch the cols used for create/update, stored as an array
	 *
	 * @return array Array of column names to use with create/update
	 */
	public function getCreateCols() {
		return $this->createCols;
	}
	
	
	/**
	 * Parses through the $results array and assigns each value to its corresponding propery
	 *
	 * @param array $result The array of model data/property values
	 */
	public function assignFromResult($result)
	{
		foreach($result as $key => $value) {
			if(property_exists(get_called_class(),$key)) {
				$this->$key = $value;
			}
		}
	}
	
	
	/**
	 * Assigns a model object by fetching a representation from the database, based on Id
	 *
	 * @param int $id The id-value of the model table
	 */
	public function assignFromDb($id)
	{
		$sql = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $this->idField . ' = ' . $id . ' LIMIT 1';
		$query = $this->db->createQuery($sql);
		$rs = $query->execute();
		$a = $rs->getArray();
		$this->assignFromResult($a[0]);

		unset($sql);
		unset($query);
		unset($rs);
		unset($a);
	}
	
	
	/**
	 * Fetches all rows from the database and returns an array of model objects
	 *
	 * @return array An array of model objects
	 */
	static public function fetchAll()
	{
		$c = get_called_class();
		$cO = new $c();
		
		$objArr = array();
		
		$sql = 'SELECT * FROM ' . $cO->tableName;
		$query = $cO->db->createQuery($sql);
		$rs = $query->execute();

		while($rs->next()) {
			$o = new $c($rs->getAllFields());
			$objArr[] = $o;
		}

		unset($c);
		unset($cO);
		unset($sql);
		unset($query);
		unset($rs);

		return $objArr;
	}
	
	
	/**
	 * Fetches all rows matching the id. Primarily used for 1:n relations with foreign keys.
	 *
	 * @return array An array of model objects
	 */
	static public function fetchById($id)
	{
		$c = get_called_class();
		$cO = new $c();
		
		$objArr = array();
		$sql = 'SELECT * FROM ' . $cO->tableName . ' WHERE ' . $cO->idField . ' = ' . $id;
		$query = $cO->db->createQuery($sql);
		$rs = $query->execute();
		while($rs->next()) {
			$o = new $c($rs->getAllFields());
			$objArr[] = $o;
		}
		unset($sql);
		unset($query);
		unset($rs);

		return $objArr;
	}
	
	
	/**
	 * Creates a new modelobject in the database
	 *
	 * @param object $model The object representation of a model
	 * @return object An instance of the new model object
	 */
	public function create($modelData)
	{
		$sql = 'INSERT INTO ' . $this->tableName . ' (';
		$c = get_called_class();
		
		$keys = array();	// Holds our keys
		$vals = array();	// Holds our values
		
		$sqlValues = '';
		
		// Loop through the input and fill $keys and $vals
		foreach($modelData as $key => $value) {
			if(array_key_exists($key, $this->createCols)) {
				$keys[] = $key;
				$vals[] = $value;
			}
		}
		
		// Loop through the keys and start creating the sql query
		for($i = 0; $i < count($keys); $i++)
		{
			$sql .= $keys[$i];
			$sqlValues .= '?';
			if($i+1 < count($keys))
			{
				$sql .= ',';
				$sqlValues .= ',';
			}
		}
		
		$sql .= ') VALUES (' . $sqlValues . ')';
		
		$query = $this->db->createQuery($sql);
		
		// Loop through the values and call setParameter for each val
		for($i = 0; $i < count($vals); $i++)
		{
			$query->setParameter($i+1, $vals[$i]);
		}
		
		// Execute the query
		$rs = $query->execute();
		
		// Create a new modelobject from the insertId
		$o = new $c($rs->getInsertId());

		unset($sql);
		unset($query);
		unset($rs);
		
		// Return the new object
		return $o;
	}
	
	
	/**
	 * Update a model post
	 *
	 * @param int id The id of the post to be updated
	 * @param array modelData An array with the data to update the post with
	 */
	public function update($id, $modelData)
	{
		$sql = 'UPDATE ' . $this->tableName . ' SET ';
		$c = get_called_class();
		
		$keys = array();	// Holds our keys
		$vals = array();	// Holds our values
		
		// Loop through the input and fill $keys and $vals
		foreach($modelData as $key => $value) {
			if(array_key_exists($key, $this->createCols)) {
				$keys[] = $key;
				$vals[] = $value;
			}
		}
		
		// Loop through the keys and start creating the sql query
		for($i = 0; $i < count($keys); $i++)
		{
			$sql .= $keys[$i] . ' = ?';
			if($i+1 < count($keys))
			{
				$sql .= ',';
			}
		}
		
		$sql .= ' WHERE ' . $this->idField . ' = ?';
		
		$query = $this->db->createQuery($sql);
		
		// Loop through the values and call setParameter for each val
		for($i = 0; $i < count($vals); $i++)
		{
			$query->setParameter($i+1, $vals[$i]);
		}
		
		$query->setParameter(count($vals)+1, $id);
		
		// Execute the query
		$rs = $query->execute();
		
		// Create a new modelobject from the insertId
		$o = new $c($modelData);

		unset($sql);
		unset($query);
		unset($rs);
		
		// Return the new object
		return $o;
	}
	
	
	/**
	 * Delete an object from the database
	 *
	 * @param int $id The id value of the post to delete
	 */
	public function delete($id)
	{
		$sql = 'DELETE FROM ' . $this->tableName . ' WHERE ' . $this->idField . ' = ?';
		$query = $this->db->createQuery($sql);
		$query->setParameter(1, $id);
		$rs = $query->execute();
	}
	
	
	/**
	 * Searches the model table for certain criteria
	 *
	 * @param string $criteria The Where-clause for the search
	 */
	static public function search($criteria) {
		
		$c = get_called_class();
		$cO = new $c();
		
		$objArr = array();
		$sql = 'SELECT * FROM ' . $cO->tableName . ' WHERE ' . $criteria;
		$query = $cO->db->createQuery($sql);
		$rs = $query->execute();
		while($rs->next()) {
			$o = new $c($rs->getAllFields());
			$objArr[] = $o;
		}

		unset($c);
		unset($cO);
		unset($sql);
		unset($query);
		unset($rs);

		return $objArr;	
		
	}
}
?>