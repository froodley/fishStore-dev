<?php

namespace fishStore\Model\admin;


/**
 * usersCollection
 *
 * The model for the Users collection
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class usersCollection extends \fishStore\Base\Model
{
	
	public $usr_coll = null;
	
	/*
	 * __construct
	 *
	 * Create the model object, using the data if provided
	 *
	 * @param (array) The data needed to create the model
	 * @return (\fishStore\Base\Model) The model
	 */
	public function __construct( $data = null )
	{
		global $dbh;
		
		if( !is_array( $data ) )
		{
			LogMessage( 'Error: \Model\admin\usersCollection.php - Did not receive the required $data object.' );
			return;
		}
		
		$cols		= isset( $data['cols'] )		? implode( ',', $data['cols'] ) : '*';
		$where		= isset( $data['where'] )		? ' ' . $data['where'] : '';
		$pg			= isset( $data['pg'] )			? $data['pg'] : 1;
		$rows		= isset( $data['rows'] ) 		? $data['rows'] : 10;
		$order_by	= isset( $data['order_by'] )	? ' ' . $data['order_by'] : '';
		
		if( !is_int( $pg ) || !is_int( $rows ) )
		{
			LogMessage( 'Error: \Model\admin\usersCollection.php - Did not receive the required $data object.' );
			return;
		}
		
		//$this->page = $pg;
		$paging = " LIMIT $rows OFFSET " . ( ( $pg - 1 ) * $rows );
		
		$sql ="SELECT $cols FROM tbl_user" . $where . $order_by . $paging;
		
		$res = $dbh->Select( $sql, [] );
		
		if( !$res )
			return; // DBH will have logged the error
		
		$usr = new \fishStore\Entity\User();
		$tbl_info = $usr->table_info;
		
		foreach( $res as $row )
		{
			$usr = new \fishStore\Entity\User();
			foreach( $row as $col => $val )
			{
				if( $tbl_info[$col] == 'tinyint' )
					$val = ( $val === 1 ) ? true : false;
				
				$usr->$col = $val;
			}
			
			$this->usr_coll[] = $usr;
		}
		
		
	} // __construct
	
	
} // usersCollection
