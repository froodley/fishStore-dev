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
abstract class Entity
{
	
	public static function Update( $obj )
	{
		global $dbh;
		
		$pk = $obj->table_info['pk'];
		$assignments = [];
		foreach( $obj->dirty_cols as $prop )
		{
			if ( $prop == $pk ) // Erroneous use of $dirty_cols
				continue;
			
			$assignments[ $prop ] = $obj->$prop;
		}
		
		$res = $dbh->Update( $obj->table_name, $assignments, "$pk = ?", [ $obj->$pk ] );
		
		if( $res )
		{
			$obj->dirty_cols = [];
			return true;
		}
		else
			return false;
	}
	
	public function Delete( $obj )
	{
		global $dbh;
		
		$pk = $obj->table_info['pk'];
		
		return $dbh->Delete( $obj->table_name, "$pk = ?", [ $obj->$pk ] );
	}
}
