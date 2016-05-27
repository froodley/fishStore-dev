<?php

namespace fishStore\Interfaces;

/**
 * iModel
 *
 * Interface for Models; the entities, collections, etc. required for a view
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
interface iModel
{
	
	/*
	 * __construct
	 *
	 * Create the model object, using the data if provided
	 *
	 * @param (var) The data needed to create the model
	 * @return (\fishStore\Base\Model) The model
	 */
	public function __construct( $data = null);
	
} // iModel