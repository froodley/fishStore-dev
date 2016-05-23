<?php

namespace fishStore\Interfaces;

/**
 * iView
 *
 * Interface for view objects defines the contract
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
interface iView
{
	/**
	* GetHTML
	*
	* Return the HTML to display
	*
	* @param (fishStore\Base\Model) The data model used for the display, if any
	* @return (string) The output HTML
	*/
	public function GetHTML( \fishStore\Base\Model $model = null );
	
	
	/**
	* GetDependencies
	*
	* Return a MDA of ['js'] and ['css'] dependencies to require for this view
	*
	* @return (array) The dependency MDA
	*/
	public function GetDependencies();
	
	/**
	* InjectDependencies
	*
	* When returning views through ajax that have dependencies, inject them through inline script tags
	*
	* @param (array) The dependencies array, must have 'js' and 'css'
	* @return (string) The HTML to inject
	*/
	public static function InjectDependencies( $dependencies );
	
} // iView