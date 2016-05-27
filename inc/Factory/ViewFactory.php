<?php

namespace fishStore\Factory;

/**
 * ViewFactory
 *
 * ViewFactory creates and returns views
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class ViewFactory
{
	/**
	* Make
	*
	* Creates and returns the view output
	*
	* @param (string) Either a view name (default is Index) or a Model object
	* @param (Model) The Model object associated with the view
	* @return (string) The output HTML
	*/
	public static function Make( $name_or_model = null, \fishStore\Model $model = null )
	{
		global $base_path, $internal_error, $ini, $_ENVELOPE;
		
		// Get view name
		$view_space = self::_getViewSpace();
		$view_name = 'Index';
		$view_model = null;
		if( !is_null( $name_or_model ) )
		{
			if( is_string( $name_or_model ) )
			{
				$view_name = $name_or_model;
				$view_model = isset( $model ) ? $model : null;
			}
			elseif( is_object( $name_or_model ) &&
				   class_implements( get_class( $name_or_model ), 'fishStore\\Interfaces\\iModel' ) )
				$view_model = $name_or_model;
			else
			{
				LogMessage( "Warn: ViewFactory.php - Received an invalid parameter: " . $name_or_model );
			}
		}
		
		if( is_null( $view_model ) && !is_null( $model ) )
			$view_model = $model;
		
		// Check and instantiate View class
		$class_name = 'fishStore\\View\\' . $view_space . '\\' . $view_name;
		
		$implements = class_implements( $class_name );
		if( !isset( $implements[ 'fishStore\Interfaces\iView' ] ) )
		{
			LogMessage( "Error: Factory\View - View '$class_name' does not implement fishStore\Interfaces\iView.");
			exit( sprintf(	$internal_error, $ini['STORE']['NAME'],
							"ViewFactory", $ini['STORE']['E-MAIL'] ) );
		}
		
		# class_implements() has already called spl_autoload_register, so there is no reason to require_once
		
		$view = new $class_name();
		
		// Set title, etc.
		$_ENVELOPE['title'] = $ini['STORE']['NAME'] . ' - ' . $view_space;
		
		// Populate the view-specific dependency lists
		$_ENVELOPE['dependencies'] = $view->GetDependencies();
		
		return $view->GetHTML( $view_model );
	
	} // Make
	
	
	/**
	* _getViewSpace
	*
	* Discover the correct view collection to look in
	* 
	* @return (string) The name of the view space
	*/
	private static function _getViewSpace()
	{
		$trace = debug_backtrace();
		if ( isset( $trace[2] ) ) // caller
		{
			$class_name = $trace[2]['class'];
			$view_space = preg_replace('/fishStore\\\\Controller\\\\/', '', $class_name );
			return $view_space;
		}
		else
			return 'Home'; #TODO
		
	}
	

} // ViewFactory
