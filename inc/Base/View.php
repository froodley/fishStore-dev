<?php

namespace fishStore\Base;

/**
 * View
 *
 * The abstract base class for all views
 *
 * @package    fishStore
 * @author     
 * @copyright  2016
 * @version    Release: 1.3
 */
abstract class View implements \fishStore\Interfaces\iView
{
	/**
	 * GetHTML
	 *
	 * Return a string of HTML for output of the view
	 *
	 * @param (Model) The data model for the view, if any
	 * @return (string) The finished tag
	 */
	public function GetHTML( \fishStore\Base\Model $model = null )
	{
		global $html, $Envelope;
		
		//$var = $Envelope['varname'];
		//$data = $model['varname2'];
		//$str = $html->div({ 'style' => "width:50%; margin 100px 25%;", 'id' => 'div_id' }, $var . ': ' . $data};

		return '';
	} // GetHTML
	
	
	/**
	* GetDependencies
	*
	* Return a MDA of ['js'] and ['css'] dependencies to require for this view
	*
	* @return (array) The dependency MDA
	*/
	public function GetDependencies()
	{
		return null;
		
	} // GetDependencies
	
} // ViewName