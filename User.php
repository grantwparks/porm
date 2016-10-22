<?php
/**
 * Person; User
 */

require_once 'Person.php';

/**
 * User
 */
class User extends Person {

	/**
	 * Primary email address
	 *
	 * @porm
	 * @session
	 * @get     _getUser
	 * @var     email
	 */
	public $email;

	// ------------------------------------------------------------------------
	// PUBLIC METHODS
	// ------------------------------------------------------------------------

	// ------------------------------------------------------------------------
	// DATA LAYER METHODS
	// ------------------------------------------------------------------------

	/**
	 * Gets user id from email address
	 *
	 * @param  $email  Email address
	 * @return integer User id
	 * @throws NotFoundException
	 */
	protected function _getUserIdByEmail($email) {
		$result = DAPI::singleton('DAPI_mod_user')->getUserIdByEmail($email);
		if ($result['rows'] > 0) {
			return (integer)$result['data'][0]['user_id'];
		}
		else {
			throw $this->newNotFoundException("Cannot find user {$email}.");
		}
	}
}
?>
