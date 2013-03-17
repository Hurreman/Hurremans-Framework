<?php
/**
 * Handles connection to a MySQL database
 * 
 * @author Fredrik Karlsson <fredrik@fkinnovation.se>
 * @version 1.3.0
 **/
class cMySQL
{
	/**
	 * @access protected
	 */
    protected $connectionId;
	
	/**
	 * Database name to use
	 * @access protected
	 * @var string
	 */
    protected $database;
	
	/**
	 * MySQL Server hostname/IP
	 * @access protected
	 * @var string
	 */
    protected $host;
	
	/**
	 * Server password
	 * @access protected
	 * @var string
	 */
    protected $password;
	
	/**
	 * Server username
	 * @access protected
	 * @var string
	 */	
    protected $username;

    
	/**
	 * @access public
	 * @param array $args
	 */
	public function __construct($args)
    {
		$host = $args[0];
		$database = $args[1];
		$username = $args[2];
		$password = $args[3];
		
        if(!isset($host)) { trigger_error('Undefined Host'); }
	    $this->host = $host;

	    if(!isset($database)) { trigger_error('Undefined Database'); }
        $this->database = $database;

        if(!isset($username)) { trigger_error('Undefined Username'); }
        $this->username = $username;

        if(!isset($password)) { trigger_error('Undefined Password'); }
        $this->password = $password;

        $this->open();
    }

	/**
	 * Closes the server connection
	 * @access public
	 */
    public function close()
    {
        $this->connectionId = mysql_close($this->connectionId) or trigger_error('Could not close connection: ' . mysql_error());
    }
    
	/**
	 * Creates a new Query Object
	 * @access public
	 * @param string $sql
	 */
    public function createQuery($sql)
    {
        return new cMysqlQuery($sql, $this);
    }

	/**
	 * Fetches the current connection ID
	 * @access public
	 */
    public function getConnectionId()
    {
        return $this->connectionId;
    }

	/**
	 * Opens a connection to the server and selects the specified database
	 * @access public
	 */
    public function open()
    {
        $this->connectionId = mysql_connect($this->host, $this->username, $this->password) or trigger_error('Could not connect: ' . mysql_error());
        mysql_select_db($this->database, $this->connectionId) or trigger_error('Could not select database: ' . mysql_error());
    }

}

/**
 * Handles MySQL Queries
 */
class cMysqlQuery
{
    protected $connection;
    protected $parameters = array();
    protected $sql;

    /**
	 * Construct
	 * @access public
	 * @param string $sql
	 * @param integer $connection
	 */
    public function __construct($sql, $connection)
    {
        if(!isset($connection)) { trigger_error('no connection'); }
	    $this->connection = $connection;

	    if(!isset($sql)) { trigger_error('no query'); }
        $this->sql = $sql;
    }

	/**
	 * Executes the sql query and returns a Result Object
	 * @access public
	 * @return object
	 */
    public function execute()
    {
	    $resultId = mysql_query($this->getPreparedSql(), $this->connection->getConnectionId()) or trigger_error('mySQL Query Error: ' . mysql_error() . ' : ' . $this->getPreparedSql());
	    return new MysqlResult($resultId);
    }

    /**
	 * Prepares a query by replacing question marks with their corresponding variables
	 * @access public
	 * @return string
	 */
    public function getPreparedSql()
    {
        $sql_parts = explode('?', $this->sql);
        $sql = $sql_parts[0];
        for ($i = 1, $max = count($sql_parts); $i < $max; $i++)
        {
            $sql .=  $this->parameters[$i] . $sql_parts[$i];
        }
        return $sql;
    }


	/**
	 * Adds a parameter to be replaced in the getPreparedSql function
	 * @access public
	 * @param integer $index
	 * @param string $val
	 */
    public function setParameter($index, $val, $ignore_escape = false, $str_override = false)
    {
        if(get_magic_quotes_gpc())
        {
            $val = stripslashes($val);
        }
        if(is_numeric($val) && $str_override == false)
        {
	    	$this->parameters[$index] = $val;
    	}
		elseif($ignore_escape == false && $str_override == true)
		{
			$this->parameters[$index] = "'" . mysql_real_escape_string($val) . "'";
		}		
    	elseif($ignore_escape == true && $str_override == false)
		{
			$this->parameters[$index] = $val;
		}
		else
    	{
	    	$this->parameters[$index] = "'" . mysql_real_escape_string($val) . "'";
    	}
    }

}

/**
 * Handles MySQL results
 */
class MysqlResult
{
    protected $record;
    protected $resultId;

	/**
	 * Construct
	 * @access public
	 * @param integer $sql
	 */
    public function __construct($resultId)
    {
        $this->record = array();
        $this->resultId = $resultId;
    }

	/**
	 * Closes and result and frees up resources
	 * @access public
	 */
    public function close()
    {
        mysql_free_result($this->resultId) or trigger_error('Could not free result: ' . mysql_error());
        $this->record = array();
        $this->resultId = NULL;
    }

	/**
	 * Fetches a field from the result
	 * @access public
	 * @param string $field
	 * @return mixed
	 */
    public function getField($field)
    {
        if(isset($this->record[$field]))
        {
            return $this->record[$field];
        }
        else
        {
            //trigger_error('Error: ' . $field . ' - ' . mysql_error());
            return false;
        }
    }
	
	public function getAllFields() {
		return $this->record;
	}
	
	/**
	 * Fetches a field from the result using $this->field
	 * @access public
	 * @param string $field
	 * @return mixed
	 */
	public function __get($field)
	{
		return $this->getField($field);
	}
	
	/**
	 * Returns the result as a JSON string
	 * @access public
	 * @return string
	 */
	public function getJson($root = '') {
		$rows = array();
		while($r = mysql_fetch_assoc($this->resultId)) {
			if($root != '') {
				$rows[$root][] = $r;
			}
			else {
				$rows[] = $r;
			}
		}
		return json_encode($rows);
	}
	
	public function getArray() {
		$rows = array();
		while($r = mysql_fetch_assoc($this->resultId)) {
			$rows[] = $r;
		}
		return $rows;
	}	
	
	
	public function getCsv()
	{
		$header = '';
		$data = '';
		$fields = $this->getNumFields();
		
		for ( $i = 0; $i < $fields; $i++ )
		{
		    $l = mysql_field_name( $this->resultId , $i );
			$header .= '"' . $l . '"' . ';';
		}

		while( $row = mysql_fetch_row( $this->resultId ) )
		{
		    $line = '';
		    foreach( $row as $value )
		    {                                            
		        $value = str_replace( '"' , '""' , $value );
		        $value = '"' . $value . '"' . ";";
		        $data .= $value;
		    }
			$data .= "\n";
		}
		$data = str_replace("\r", "", $data);
		
		echo "$header\n$data";	
	}
	
	
	/**
	 * Returns the number of fields in a result set.
	 * @access public
	 * @return integer
	 */
    public function getNumFields()
    {
        return mysql_num_fields($this->resultId);
    }

	/**
	 * Returns the identified (id) of the newly executed sql-query
	 * @access public
	 * @return integer
	 */
    public function getInsertId()
    {
        return mysql_insert_id();
    }

	/**
	 * Returns the number of rows in a result set.
	 * @access public
	 * @return integer
	 */
    public function getRowCount()
    {
        return mysql_num_rows($this->resultId);
    }

	/**
	 * Returns the number of affected rows in a result set.
	 * @access public
	 * @return integer
	 */
    public function getAffectedRows()
    {
        return mysql_affected_rows($this->resultId);
    }

	/**
	 * Steps forward in the records array
	 * @access public
	 * @return boolean
	 */
    public function next()
    {
        $this->record = mysql_fetch_assoc($this->resultId);
        return ($this->record !== FALSE);
    }

}
?>