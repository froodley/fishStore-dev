<?php

namespace fishStore\Util;

/**
 * Session
 *
 * Session-related functions
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Session
{
	
	/*
	 * Restore
	 *
	 * If the current session id exists in the DB and the timeout hasn't passed, spin the user back up
	 *
	 * Success indicated by presence of $_SESSION['usr']
	 * 
	 * @return (null)
	 */
	public static function Restore()
	{
		global $dbh;
		
		$session_id = session_id();
		
		if( $session_id && !isset( $_SESSION['usr'] ) )
		{
			$rows = self::_gatherAndDestroyTimeouts( $session_id );
			
			if( count( $rows ) == 1 )
			{
				// Success, spin up the session
				$usr = \fishStore\Base\Entity::GetByID( 'User', $rows[0]['session_usr_id'] );
				if( !$usr )
				{
					LogMessage( "Error: Session.php - The user with ID#{$rows[0]['session_usr_id']} for session ID#{$session_id} no longer exists." );
					return;
				}
				else
				{
					session_id( $rows[0]['session_id'] ); // Changes if the valid session was matched by session_user_id
					$_SESSION['usr'] = $usr;
					
					return;
				}
				
			}
			elseif( count( $rows ) > 1 )
			{
				$del = $dbh->Delete( 'tbl_session', 'session_id = ?', [ $session_id ]);
				if( $del )
					LogMessage(	"Error: Session.php - Multiple ($del) active rows existed in the session table for session ID# $session_id; all have been deleted." );
				else
					LogMessage(	"Error: Session.php - Multiple ($del) active rows existed in the session table for session ID# $session_id; they could not be deleted." );
					
				return; // Non-critical failure #TODO This should notify the user
			}
			else
			{
				// No session, no restore
				return;
			}
		}
		elseif( isset( $_SESSION['usr'] ) )
		{
			// No reason to restore
			return;
		}
		else
		{
			LogMessage(	"Error: Session.php - A session id wasn't available in ::Restore" );
			return; // Non-critical failure #TODO This should notify the user
		}
	
	} // Restore
	
	/*
	 * Store
	 *
	 * Store the current session to the database
	 *
	 * @return (boolean) Result of attempt
	 */
	public static function Store()
	{
		global $dbh;
		
		$session_id = session_id();
		
		if( $session_id && isset( $_SESSION['usr'] ) )
		{
			$rows = self::_gatherAndDestroyTimeouts( $session_id );
			
			if( !count( $rows ) )
			{
				return $dbh->Insert( 'tbl_session', [
												'session_id' => $session_id,
												'session_usr_id' => $_SESSION['usr']->usr_id,
												'session_created' => date( 'Y-m-d H:i:s' )
											 ]);
			}
		}
		else
		{
			LogMessage(	"Error: Session.php - Either session id or user id was missing while storing session." );
			return false;
		}
	
	} // Store
	
	
	/*
	 * End
	 *
	 * Destroy a session both locally and on the DB
	 *
	 * @return (null)
	 */
	public static function End( )
	{
		global $dbh;
		
		$session_id = session_id();
		$del = $dbh->Delete( 'tbl_session', 'session_id = ?', [ $session_id ] );
		
		if( !$del )
		{
			LogMessage("Error: Util\Session.php - Could not delete session '$session_id' from the database.");
			
			//Failover - modify the user ID to prevent Restore
			$upd = $dbh->Update( 'tbl_session', [ 'session_usr_id', 0 ], 'session_id = ?', [ $session_id ] );
			if( !$upd )
				LogMessage("Critical Error: Util\Session.php - Failover attempt to modify session '$session_id' also failed.");
		}
		
		session_unset();
		session_destroy();
		
	} // End
	
	
	/*
	 * _gatherAndDestroyTimeouts
	 *
	 * Gathers rows matching the available information, destroying any matching timed-out sessions
	 *
	 * @param (string) The session ID to check with
	 * @return (array) [ an array of remaining rows (should be 1 or 0) ]
	 */
	private static function _gatherAndDestroyTimeouts( $session_id )
	{
		global $dbh, $ini;
		
		$rows = [];
		
		$res = null;
		// Gather rows
		if( isset( $_SESSION['usr'] ) )
		{
			$res = $dbh->Select(	$sql =	'SELECT * FROM ' .
									'tbl_session WHERE session_id = ? OR session_usr_id = ?',
									[ $session_id, $_SESSION['usr']->usr_id ] );
		}
		else
		{
			$res = $dbh->Select(	$sql =	'SELECT * FROM ' .
									'tbl_session WHERE session_id = ?',
									[ $session_id ] );
		}
		
		// Look for expired
		if( !is_null( $res ) && count($res) > 0 )
		{
			$now = time();
			$timeout = $ini['SETTINGS']['SESSION_TIMEOUT'] * 60;
			
			foreach( $res as $row )
			{
				// Check session timeout
				$created = strtotime( $row['session_created'] );
				
				if( ( $now - $created ) > $timeout )
				{
					// Session timeout
					$del = $dbh->Delete( 'tbl_session', 'session_id = ? AND session_usr_id = ?',
										[ $row['session_id'], $row['session_usr_id'] ]);
					if( !$del )
					{
						LogMessage( "Error: Session.php - Could not delete an expired row. Session #{$row['session_id']}, Usr#{$row['session_usr_id']}" );
						$rows[] = $row;
					}
				}
				else
					$rows[] = $row;
			}
		}
		
		return $rows;
	
	} // _gatherAndDestroyTimeouts
	
} // Session

