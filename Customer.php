<?php
/**
 * Person; Customer
 */

require_once 'Person.php';

/**
 * Customer
 */
class Customer extends Person {

	/**
	 * Address 1
	 *
	 * @managed <== FIXME this token should be configurable
	 * @var string
	 */
	public $address1;

	/**
	 * Address 2
	 *
	 * @managed
	 * @var string
	 */
	public $address2;

	/**
	 * City
	 *
	 * @managed
	 * @var string
	 */
	public $city;

	/**
	 * Contact email
	 *
	 * @managed
	 * @var string
	 */
	public $emails = array();

	/**
	 * Country name
	 *
	 * @managed
	 * @var string
	 */
	public $country;

	/**
	 * Creation timestamp
	 *
	 * @managed
	 * @var timestamp
	 */
	public $createdOn;

	/**
	 * Name
	 *
	 * @managed
	 * @var string
	 */
	public $name;

	/**
	 * Post code
	 *
	 * @managed
	 * @var string
	 */
	public $postCode;

	/**
	 * Customer type
	 *
	 * @managed
	 * @var integer
	 */
	public $type;

};