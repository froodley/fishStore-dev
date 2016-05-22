<?php

namespace fishStore\Util;

define( 'TABLE_NAME_ERROR', "SQL Error: Improper table name provided.  All tables have the format 'tbl' followed by only letters. Provided: %s");
define( 'PREP_ERROR', "SQL Error: Couldn't prepare '%s'. Error: # %d - %s" );
define( 'UNSAFE_ERROR', "SQL Error: A parameter was hard-coded; all parameters should be '?'. SQL: %s" );


/**
 * DBH
 *
 * The database CRUD class
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class DBH
{
	#TODO: Convert to PDO?
	
	private $_dbh = null;
	
	/**
	* __construct
	*
	* Connects and fills the internal $_dbh handle
	*
	* @param (string) The server address
	* @param (string) The user name
	* @param (string) The password
	* @param (string) The database name
	* @return (DBH) The DBH object
	*/
	public function __construct( $server, $user, $pass, $db )
	{
		$this->_dbh = mysqli_connect( $server, $user, $pass, $db );
		
		if ( $this->_dbh->connect_errno )
		{
			LogMessage('Failed SQL conn: ' . $this->_dbh->connect_errno . ' : ' . $this->_dbh->connect_error);
			return null;
		}
		
	} // __construct
	
	
	/**
	* Select
	*
	* Return a set of one or more rows from the provided SQL query
	*
	* @param (string) The SQL query; must be ?-parameterized
	* @param (array) An array of values to fill the params
	* @return (array) A MDA of associative-array rows
	*/
	public function Select( $sql, $sql_prms )
	{
		#TODO: Make SQL builder for better safety
		
		$dbh = $this->_dbh;
		
		if( !CheckSQL( $sql ) )
		{
			LogMessage( sprintf( UNSAFE_ERROR, $sql ) );
			return false;
		}
		
		$sth = $dbh->prepare( $sql );
		if( !$sth )
		{
			LogMessage( sprintf( PREP_ERROR, $sql, $dbh->errno, $dbh->error ) );
			return false;
		}
		
		$this->BindParams( $sth, $args );
		
		$sth->execute();
		
		$rs = $sth->get_result();
		$results = [];
		while( $row = $rs->fetch_array(MYSQLI_ASSOC) )
			array_push( $results, $row );

		return $results;
	
	} // Select
	
	
	/**
	* Insert
	*
	* Insert one or more rows into the provided table
	*
	* @param (string) The table name
	* @param (array) An array or MDA representing the row or rows
	* @return (var) Either false, or the number of rows inserted
	*/
	public function Insert( $tbl, $rows )
	{
		$dbh = $this->_dbh;
		
		if( !self::CheckTableName( $tbl ) )
		{
			LogMessage( sprintf( TABLE_NAME_ERROR, $tbl ) );
			return false;
		}
		
		$cols = array_keys( $row );
		$vals = array_values( $row );
		$cnt = count( $cols );
		
		$sql = "INSERT INTO $tbl ( ";
		for( $i = 0; $i < $cnt; $i++ )
		{
			$sql .= $cols[$i];
			$sql .= ( $i < $cnt - 1 ) ? ', ' : ' ';
		}
		$sql .= " ) VALUES ( ";
		for( $i = 0; $i < $cnt; $i++ )
		{
			$sql .= '?';
			$sql .= ( $i < $cnt - 1 ) ? ', ' : ' ';
		}
		
		$sql .= ")";
		
		return $this->_execute( $sql, $vals, 'Insert' );
		
	} // Insert
	
	/**
	* Update
	*
	* Update one or more rows from the provided table
	*
	* @param (string) The table name
	* @param (array) An associative array of column names and values
	* @param (string) The WHERE clause, must be ?-parameterized
	* @param (array) The values to fill the WHERE clause
	* @return (var) Either false, or the number of rows updated
	*/
	public function Update( $tbl, $assignments, $where, $where_prms )
	{
		$dbh = $this->_dbh;
		
		if( !self::CheckTableName( $tbl ) )
		{
			LogMessage( sprintf( TABLE_NAME_ERROR, $tbl ) );
			return false;
		}
		
		$assignments_str = '';
		$i = 0;
		$cnt = count($assignments);
		foreach( $assignments as $k => $v )
		{
			$assignments_str .= "$k = ?";
			$assignments_str .= ( $i < $cnt - 1 ) ? ', ' : '';
			array_unshift( $where_prms, $v);
			$i++;
		}
		
		$sql = "UPDATE $tbl SET $assignments_str WHERE $where";
		
		return $this->_execute( $sql, $where_prms, 'Update' );
		
	} // Update
	
	/**
	* Delete
	*
	* Delete one or more rows from the provided table
	*
	* @param (string) The table name
	* @param (string) The WHERE clause, must be ?-parameterized
	* @param (array) The values to fill the WHERE clause
	* @return (var) False, or the number of rows deleted
	*/
	public function Delete( $tbl, $where, $where_prms )
	{
		$dbh = $this->_dbh;
		
		if( !self::CheckTableName( $tbl ) )
		{
			LogMessage( sprintf( TABLE_NAME_ERROR, $tbl ) );
			return false;
		}
		
		$sql = "DELETE FROM $tbl WHERE $where";
		
		return $this->_execute( $sql, $where_prms, 'Delete' );
		
	} // Delete
	
	/**
	* _execute
	*
	* Internal method executes non-Select statements
	*
	* @param (string) The SQL to execute
	* @array (var) The parameters to bind into the statement
	* @param (string) The operation being executed (Insert, Delete, Update...)
	* @return (var) False, or the number of rows deleted
	*/
	private function _execute( $sql, $prms, $operation )
	{
		$dbh = $this->_dbh;
		
		if( !self::CheckSQL( $sql ) )
		{
			LogMessage( sprintf( UNSAFE_ERROR, $sql ) );
			return false;
		}
		
		$sth = $dbh->prepare( $sql );
		if( !$sth )
		{
			LogMessage( sprintf( PREP_ERROR, $sql, $dbh->errno, $dbh->error ) );
			return false;
		}
		
		$this->_bindParams( $sth, $prms );
		
		$res = $sth->execute();
		if ( $res === false )
		{
			LogMessage("SQL Error: $operation failed for '$sql'. Error: #{$sth->errno} - {$sth->error}");
			return false;
		}
		else
			return $sth->affected_rows;
	} // _execute
	
	
	/**
	* _bindParams
	*
	* Bind an arbitrary number of values to the params on the statement handle
	*
	* @param (object) The statement handle
	* @param (array) The array of argument values
	* @return (null)
	*/
	private function _bindParams( $sth, $args )
	{
		$bind_prms = [];
		$prm_types = '';
		for( $i = 0; $i < count( $args ); $i++ )
		{
			$arg = $args[$i];
			if( is_int( $arg ) )
				$prm_types .= 'i';
			elseif( is_float( $arg ) )
				$prm_types .= 'd';
			else
				$prm_types .= 's';
			
			$bind_prms[] = &$args[$i];
		}
		
		array_unshift( $bind_prms, $prm_types );
		
		
		call_user_func_array( array( $sth, 'bind_param' ), $bind_prms );
		
	} // BindParams
	
	
	/**
	* CheckSQL
	*
	* Check that no hard-coded parameters exist in the SQL
	*
	* @param (string) The SQL string to check
	* @return (boolean) The result
	*/
	public static function CheckSQL ( $sql )
	{
		preg_match( '/=\s*[^\?\s]/', $sql, $matches );
		return count( $matches ) ? false :  true;
	
	} // CheckSQL
	
	
	/**
	* CheckTableName
	*
	* Check that the provided table name is properly formatted.  All tables
	* should be 'tbl' followed by a string of letters only
	*
	* @param (string) The table name string to check
	* @return (boolean) The result
	*/
	public static function CheckTableName( $table_name )
	{
		preg_match('/tbl[a-zA-Z_]+/', $table_name, $matches );
		return count( $matches ) == 1 ? true : false;
	
	} // CheckTableName
	
	
	/**
	* GetHandler
	*
	* Returns the internal $_dbh handle; for experienced consumers only
	*
	* @return (null)
	*/
	public function GetHandle()
	{
		$die_msg = 'No database handle available';
		
		if( !$this->_dbh )
		{
			LogMessage( $die_msg );
			return null;
		}
		
		return $this->_dbh;
	
	} // GetHandle
	
	
	/**
	* __destruct
	*
	* Disconnect the $_dbh handle if necessary
	*
	* @return (null)
	*/
	public function __destruct()
	{
		if( $this->_dbh )
			$this->_dbh->close();
			
	} //  __destruct
	
} // DBH
