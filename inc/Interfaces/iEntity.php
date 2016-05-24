<?php

namespace fishStore\Interfaces;

/**
 * iEntity
 *
 * Interface for Entity ORM objects - since all Entities in this
 * framework are created dynamically and have 'pseudo'-inheritance,
 * these methods are static and accept a generic object
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
interface iEntity
{
	/**
	* Commit
	*
	* Creates a new entity to the database
	*
	* @param (object) The entity to commit 
	* @return (boolean) The result
	*/
	public static function Commit( $obj );
	
	/**
	* Update
	*
	* Updates an entity in the database
	*
	* @param (object) The entity to update 
	* @return (boolean) The result
	*/
	public static function Update( $obj );
	
	/**
	* Delete
	*
	* Deletes an entity from the database
	*
	* @param (object) The entity to delete 
	* @return (boolean) The result
	*/
	public static function Delete( $obj );
	
	
	/**
	* ToArray
	*
	* Parse an Entity into an array
	*
	* @param (object) The Entity to parse 
	* @return (array) The Entity as an array
	*/
	public static function ToArray( $obj );
	
	/**
	* Validate
	*
	* Determine whether an Entity's values match the declared data types
	*
	* @param (object) The Entity to validate
	* @return (boolean) The result
	*/
	public static function Validate( $obj );
	
	
} // Controller