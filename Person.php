<?php
/**
 * Person
 *
 * @author grantwparks@gmail.com
 */

require_once 'PormModel.php';


/**
 * Person
 */
abstract class Person {

	/**
	 * Common constructor.
	 *
	 * All of the portal objects call this to be instantiated.
	 *
	 * @param  integer $id
	 * @return object
	 */
	function __construct($id, $Container = false) {
		PormModel::Init($this);
	}

	// ------------------------------------------------------------------------
	// PUBLIC METHODS
	// ------------------------------------------------------------------------

	// ------------------------------------------------------------------------
	// PROPERTY METHODS (get_ and set_) in order of property name
	//
	// Used my multiple entities.
	// ------------------------------------------------------------------------

	// ------------------------------------------------------------------------
	// DATA METHODS
	// ------------------------------------------------------------------------
}
