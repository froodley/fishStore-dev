<?php

namespace fishStore\Factory;

define( 'YAML_FN', $GLOBALS['base_path'] . '\\etc\\YAML\\db.yaml' );

/**
 * EntityFactory
 *
 * Dynamic entity framework; spins up classes for tables from YAML
 * INI setting encrypt_entities will cause the YAML to be RSA encrypted
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class EntityFactory
{
	private static $_error_prefix = 'Error - EntityFactory.php :';
	private static $_js_fh = null;
	/**
	* LoadEntities
	*
	* Spins up the entity classes from db.yaml
	*
	* @param (array) The array ref to load into; will contain a SDA of strings
	* @return (null)
	*/
	public static function LoadEntities( &$entities )
	{
		global $ini, $inc_path;
		
		$entities = []; // Clear the list
		
		// Get definitions from YAML
		
		if( !file_exists( YAML_FN ) )
			self::_updateDefinitions();
		if( !file_exists( YAML_FN ) )
			return;
		
		$entity_defs = [];
		\fishStore\Util\YAML::Load( $entity_defs, YAML_FN, $ini['DB']['ENCRYPT_ENTITIES'] );
		
		// Clear the JS models
		self::$_js_fh = \fishStore\Util\File::OpenWrite( $inc_path . '\\js\\models.js', 'EntityFactory' );
		
		// Create classes
		foreach( $entity_defs as $table_name => $members )
		{
			// Verify schema
			if( !is_array( $members ) || strpos( $table_name, 'tbl_') != 0 )
			{
				LogMessage( self::$_error_prefix . " Malformed entity definition for $table_name discarded." );
				continue;
			}
			
			// Generate class
			$class_name = substr( $table_name, 3 ); // strip tbl from the beginning
			$class_name = preg_replace_callback( '/\_([a-z])/', function( $matches ) { return strtoupper( $matches[1] ); }, $class_name);
			
			if( self::_createEntity( $table_name, $class_name, $members ) )
				$entities[] = $class_name;
		}
		
		fclose( self::$_js_fh );
		
		if( !count( $entities ) )
		{
			LogMessage( self::$_error_prefix . " No entity classes were created." );
			return;
		}
		
		#TODO: Verify the controllers' entities all exist
		
	} // LoadEntities

	
	/**
	* _createEntity
	*
	* Creates the entity class for a given table definition
	*
	* @param (string) The entity's table name
	* @param (string) The entity's class name
	* @param (array) The table's members, associative array as: name => data type
	* @return (null)
	*/
	private static function _createEntity( $table_name, $class_name, $members )
	{
		global $ini;
		
		$members_str = ArrayToStr( $members );
		$create_str =	"namespace fishStore\Entity; class $class_name {" .
						"public \$table_name = '$table_name'; public \$table_info = $members_str; public \$dirty_cols = [];";
		
		$js_model = "function model_$class_name() {";
		if( !$ini['SETTINGS']['MINIFY'] )
				$js_model .= "\r\n";
		
		foreach( $members as $k => $v )
		{
			if( $k == 'pk' )
				continue;
			
			if( is_array( $v ) )
			{
				LogMessage( self::$_error_prefix . " Malformed entity member definition for $k discarded." );
				continue;
			}
			
			$create_str	.= " public \${$k} = null;";
			$js_model	.= " this.$k = '';";
			if( !$ini['SETTINGS']['MINIFY'] )
				$js_model .= "\r\n";
		}
		$js_model .= '}';
		if( !$ini['SETTINGS']['MINIFY'] )
				$js_model .= "\r\n\r\n";
				
		$js_model .= "function model_{$class_name}Factory() { return { Create: function(){ return new model_$class_name();} } }";
		if( !$ini['SETTINGS']['MINIFY'] )
				$js_model .= "\r\n\r\n";
		
		// Code to implement dynamic 'inheritance' functionality
		$create_str .=	' private static $method_arr = [];' .
		
						' public static function _registerMethod( $method ){ if( !in_array( $method, self::$method_arr ) )' .
						' { self::$method_arr[] = $method; return true; } return false; }' .
						
						' public function __call( $method, $args ) { if( in_array( $method, self::$method_arr ) )' .
						' { return call_user_func_array( "fishStore\\\\Base\\\\Entity::" . $method, [$this]); } }' ;
		
		$create_str .= ' } return true;';
		
		// Create the class
		$res = eval( $create_str );
		
		if( !$res )
			LogMessage( self::$_error_prefix . " Class creation failed for table $class_name." );
		
		self::_registerMethods( $class_name );
		
		// Create the js model
		fwrite( self::$_js_fh, $js_model );
		
		return $res;
	} // _createEntity
	
	
	/**
	* _registerMethods
	*
	* Dynamic classes cannot inherit.  Here, all entities become 'descendants' of the 'abstract' class Entity,
	* although they will not reflect as such
	*
	* @return (null)
	*/
	private static $_entity_members = null;
	private static function _registerMethods( $class_name )
	{
		if( !isset( self::$_entity_members ) )
			self::$_entity_members = GetClassMembers( 'fishStore\Base\Entity' );
		
		$class_name = 'fishStore\\Entity\\'. $class_name;
		
		foreach( self::$_entity_members['methods'] as $method )
		{
			$name = $method->getName();
			$class_name::_registerMethod( $name );
		}
	} // _registerMethods
	
	
	/**
	* _updateDefinitions
	*
	* Updates the db.yaml file with the current definitions from the database
	*
	* @return (null)
	*/
	private static function _updateDefinitions()
	{
		global $dbh, $ini;
		
		$handle = $dbh->GetHandle();
		if( !$handle )
		{
			LogMessage(self::$_error_prefix . " Could not retrieve the DBH handle when trying to update the entity definitions." );
			return;
		}
		
		$table_list = [];
		
		
		// Gather table list
		$res = $handle->query( "SHOW TABLES" );
		if ( !$res )
		{
			LogMessage( self::$_error_prefix . " Could not retrieve the table list from the database. Err: #{$handle->errno} : {$handle->error}" );
			return;
		}
		
		// Database empty (first run, eg); attempt to create tables from stored SQL
		if( $res->num_rows == 0 )
		{
			$created = self::_createTables( $handle );
			if( !$created )
				return;
			
			$res = $handle->query( "SHOW TABLES" );
			if ( !$res || $res->num_rows == 0 )
			{
				LogMessage( self::$_error_prefix . " Could not retrieve the table list from the database. Err: #{$handle->errno} : {$handle->error}" );
				return;
			}
		}

		while( $row = $res->fetch_row() )
			$table_list[] = $row[0];
		
		
		// Build the entity definitions
		$entity_defs = [];
		
		foreach( $table_list as $table_name )
			self::_buildTableDef( $handle, $table_name, $entity_defs );
		
		if( count( $entity_defs ) == 0 )
		{
			LogMessage( self::$_error_prefix . " Did not retrieve any entity definitions from the database. Err: #{$handle->errno} : {$handle->error}");
			return;
		}
		
		\fishStore\Util\YAML::Write( $entity_defs, YAML_FN, $ini['DB']['ENCRYPT_ENTITIES'] );
		
	} // _updateDefinitions
	
	
	/**
	* _buildTableDef
	*
	* Build the table definition for a given table
	*
	* @param (string) The table name
	* @param (array ref) The entity_defs array
	* @return (null)
	*/
	private static function _buildTableDef( $handle, $table_name, &$entity_defs )
	{
		global $ini;
		preg_match( '/tbl[a-zA-Z_]+/', $table_name, $matches );
		if( count( $matches ) != 1)
			continue; // not a fishStore table
		
		// Get PK
		$sql = "SHOW INDEX FROM $table_name WHERE Key_name = 'PRIMARY'";
		
		$res = $handle->query( $sql );
		if( !$res || $res->num_rows != 1 )
		{
			LogMessage( self::$_error_prefix . " Could not retrieve the primary key for table $table_name. Err: #{$handle->errno} : {$handle->error}" );
			return;
		}
		$row = $res->fetch_assoc();
		$pk = $row['Column_name'];
		if( !strlen( $pk) )
		{
			LogMessage( self::$_error_prefix . " Could not retrieve the primary key for table $table_name. Err: #{$handle->errno} : {$handle->error}" );
			return;
		}
		
		
		// Get column definitions
		$sql =	"SELECT column_name, data_type FROM information_schema.columns WHERE " .
				"table_schema = '{$ini['DB']['DB_NAME']}' AND table_name ='$table_name';";
		
		$res = $handle->query( $sql );
		if( !$res || $res->num_rows == 0 )
		{
			LogMessage( self::$_error_prefix . " Could not retrieve the column list for table $table_name. Err: #{$handle->errno} : {$handle->error}" );
			return;
		}
		
		$entity_defs[$table_name] = [];
		$entity_defs[$table_name]['pk'] = $pk;
		
		while( $row = $res->fetch_assoc() )
			$entity_defs[$table_name][ $row['column_name'] ] = $row[ 'data_type' ];
		
		
	} // _buildTableDef
	
	
	/**
	* _createTables
	*
	* Create the fishStore tables from stored SQL
	*
	* @return (null)
	*/
	private static function _createTables( $handle )
	{
		global $base_path;
		
		// Create the tables from SQL
		$fn = $base_path . '\\etc\\sql\\create_tables.sql';
		
		$fsize = filesize( $fn );
		if( !file_exists( $fn ) || !$fsize )
		{
			LogMessage( self::$_error_prefix . " Could not create tables; create_tables.sql not found or empty." );
			return false;
		}
		
		$fh = \fishStore\Util\File::OpenRead( $fn, 'EntityFactory' );
		if( !$fh )
			return false;
		
		$sql = fread( $fh, $fsize );
		fclose( $fh );
		
		$create_res = $handle->multi_query( $sql );
		
		while( $handle->more_results() )
		{
			$create_res &= $handle->next_result();
		}
		
		if ( !$create_res )
		{
			LogMessage( self::$_error_prefix . " Could not create tables. Err: #{$handle->errno} : {$handle->error}" );
			return false;
		}
		
		return true;
	} // _createTables
	
} // EntityFactory
