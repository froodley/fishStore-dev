<?php

/**
 * The global output sprintf for internal errors
 * @global string internal_error
 */
$internal_error =	'<html><head><title>%1$s - Internal Error</title></head><body>' .
					'<div style="text-align: center; margin: 100px 30%%;  width: 40%%; font-family: \'Comic Sans MS\', sans-serif;">' .
					'<div style="color: #932606; font-size: 50px; height: 50px;">Oops!</div>' .
					'<div style="color: ##444; font-size: 30px;">An internal error occured; please contact an administrator.<br/>' .
					'(%2$s)<br/><br/>Contact: <a href="mailto:%3$s">%3$s</a></div>' .
					'</div></body></html>';