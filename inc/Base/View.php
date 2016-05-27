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
		global $html, $_ENVELOPE;
		
		//$var = $_ENVELOPE['varname'];
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
	
	/**
	* InjectDependencies
	*
	* When returning views through ajax that have dependencies, inject them through inline script tags
	*
	* @param (array) The dependencies array, must have 'js' and 'css'
	* @return (string) The HTML to inject
	*/
	final public static function InjectDependencies( $dependencies )
	{
		global $html;
		if( !is_array( $dependencies ) ||!isset( $dependencies['js'] ) || !isset( $dependencies['css'] ) )
		{
			LogMessage( 'Error: View::InjectDependencies did not receive a properly formatted array.' );
			return;
		}
		
		$out = '';
		foreach( $dependencies['js'] as $js )
		{
			$out .=	$html->script( [],
						"var scr = document.createElement('script');" .
						"scr.type = 'text/javascript';" .
						"scr.src = '$js';" .
						"var exists = $( 'head script[src=\"$js\"]' );" .
						"if( exists.length == 0) { $( 'head' ).append( scr ); }"
					);
		}
		
		foreach( $dependencies['css'] as $css)
		{
			$out .=	$html->script( [],
						"var lnk = document.createElement('link');" .
						"lnk.type = 'text/css';" .
						"lnk.rel = 'stylesheet';" .
						"lnk.href = '$css';" .
						"var exists = $( 'head link[href=\"$css\"]' );" .
						"if( exists.length == 0) { $( 'head' ).append( lnk ); }"
					);
		}
		return $out;
	}
	
	
} // View