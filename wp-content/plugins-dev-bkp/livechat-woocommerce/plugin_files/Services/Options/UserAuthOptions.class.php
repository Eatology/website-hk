<?php
/**
 * Class UserAuthOptions
 *
 * @package WooLiveChat\Services\Options
 */

namespace WooLiveChat\Services\Options;

/**
 * Class UserAuthOptions
 *
 * @package WooLiveChat\Services\Options
 */
class UserAuthOptions extends OptionsSet {
	/**
	 * UserToken instance.
	 *
	 * @var UserToken
	 */
	public $user_token;

	/**
	 * AuthorizedUsers instance.
	 *
	 * @var AuthorizedUsers
	 */
	public $authorized_users;

	/**
	 * UserAuthOptions constructor.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'user_token'       => UserToken::get_instance(),
				'authorized_users' => AuthorizedUsers::get_instance(),
			)
		);
	}
}
