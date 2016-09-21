<?php

namespace hypeJunction\Invite;

use ElggObject;

/**
 * @property string $email        Email address associated with this invite
 * @property array  $invite_codes Invitation code(s) assodicated with this invite
 */
class Invite extends ElggObject {

	const SUBTYPE = 'user_invite';

	/**
	 * Initialize object attributes
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = self::SUBTYPE;
	}

}
