<?php

namespace fishStore\Base;

/**
 * Entity
 *
 * 'Pseudo' abstract class defined general behavior for entity classes
 *  Because entities are created dynamically by EntityFramework,
 * 'pseudo' inheritance of Entity is acheived through _register/__call
 *
 * Only methods can be inherited by this mechanism; no members should be created here
 * 
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
abstract class Entity implements \fishStore\Interfaces\iEntity
{
	
	
	
	/**
	* GetByID
	*
	* Retrieve an Entity by its PKID
	*
	* @param (string) The class name (not the FQCN) of the Entity
	* @param (int) The id #
	* @return (boolean) The result
	*/
	public static function GetByID( $class_name, $id )
	{
		global $dbh;
		
		$fqcn = "\\fishStore\\Entity\\$class_name";
		
		if( !class_exists( $fqcn ) )
		{
			LogMessage( "Error: Base\Entity::GetByID - No entity called $class_name exists." );
			return null;
		}
		
		$ent = new $fqcn();
		$pk = $ent->table_info['pk'];
		
		$res = $dbh->Select( "SELECT * FROM {$ent->table_name} WHERE $pk = ?" , [ $id ] );
		
		if( count( $res ) !== 1)
		{
			LogMessage( "Error: Base\Entity::GetByID - No $class_name entity with ID# $id was found." );
			return null;
		}
		$row = $res[0];
		
		foreach( $ent->table_info as $col => $type )
		{
			if( $col == 'pk' )
				continue;
			
			$ent->$col = $row[$col];
		}
		
		return $ent;
		
	} // GetByID
	
	
	/*
	 * CollectionToJSON
	 *
	 * Translate an array of Entities into a JSON object
	 * 
	 *
	 * @param (array) The collection of Entities
	 * @return (string) The JSON
	 */
	public static function CollectionToJSON( $coll )
	{
		if( !count( $coll ) ) return '{}';
			
		$json_coll = [];
		$tbl_info = $coll[0]->table_info;
		$pk = $tbl_info['pk'];
		
		foreach( $coll as $ent )
		{
			$json_ent = [];
			
			foreach( $tbl_info as $col => $type )
			{
				if( $col == 'pk' || stripos( $col, 'password' ) == true )
					continue;
				
				$json_ent[$col] = $ent->$col;
			}
			
			$json_coll[] = $json_ent;
		}
		
		#TODO - Still working on pagination
		//$json_coll = [ 'pg' => $pg, 'obj' => $json_coll ];
		
		
		return json_encode( $json_coll );
		
	} // CollectionToJSON
	
	
	
	// General CRUD
	
	/**
	* Commit
	*
	* Creates a new entity to the database
	*
	* @param (object) The entity to commit 
	* @return (boolean) The result
	*/
	public static function Commit( $ent )
	{
		global $dbh;
		
		if( !self::Validate( $ent ) )
			return false;
		
		$row = self::ToArray( $ent );
		
		return $dbh->Insert( $ent->table_name, $row );
	} // Commit
	
	
	/**
	* Update
	*
	* Updates an entity in the database
	*
	* @param (object) The entity to update 
	* @return (boolean) The result
	*/
	public static function Update( $ent )
	{
		global $dbh;
		
		$pk = $ent->table_info['pk'];
		$assignments = [];
		foreach( $ent->dirty_cols as $prop )
		{
			if ( $prop == $pk ) // Erroneous use of $dirty_cols
				continue;
			
			$assignments[ $prop ] = $ent->$prop;
		}
		$res = $dbh->Update( $ent->table_name, $assignments, "$pk = ?", [ $ent->$pk ] );
		
		if( $res )
		{
			$ent->dirty_cols = [];
			return true;
		}
		else
			return false;
	} // Update
	
	
	/**
	* Delete
	*
	* Deletes an entity from the database
	*
	* @param (object) The entity to delete 
	* @return (boolean) The result
	*/
	public static function Delete( $ent )
	{
		global $dbh;
		
		$pk = $ent->table_info['pk'];
		
		return $dbh->Delete( $ent->table_name, "$pk = ?", [ $ent->$pk ] );
	} // Delete
	
	
	/**
	* ToArray
	*
	* Parse an Entity into an array
	*
	* @param (object) The entity to parse 
	* @return (array) The Entity as an array
	*/
	public static function ToArray( $ent )
	{
		$arr = [];
		foreach( $ent->table_info as $col => $type )
		{
			if( $col == 'pk')
				continue;
			
			$val = $ent->$col;
			if( is_a( $val, 'DateTime' ) )
			{
				// Convert to string
				$val = $val->format('Y-m-d H:i:s');
			}
			
			$arr[$col] = $val;
		}
		
		return $arr;
	} // ToArray
	
	/**
	* Validate
	*
	* Determine whether an Entity's values match the declared data types
	*
	* @param (object) The Entity to validate
	* @return (boolean) The result
	*/
	public static function Validate( $ent )
	{
		$success = true;
		
		foreach( $ent->table_info as $col => $type )
		{
			if( $col == 'pk' )
				continue;
			
			$val = $ent->$col;
			if( is_null( $val ) )
				continue;
			
			switch( $type )
			{
				case 'tinyint':
					$success &= is_bool( $val );
					break;
				case 'int':
					$success &= is_int( $val );
					break;
				case 'decimal':
					$success &= is_numeric( $val );
					break;
				case 'varchar':
				case 'text':
					$success &= is_string( $val );
					break;
				case 'datetime':
				case 'date':
					if( is_a( $val, 'DateTime' ) )
					{
						$success &= true; // irrelevant, for consistency
					}
					elseif( is_string( $val ) )
					{
						$date = date_parse( $val );
						$success &= $date['error_count'] == 0;
					}
					break;
			}
		}
		
		return $success;
	} // Validate
	
} // Entity
