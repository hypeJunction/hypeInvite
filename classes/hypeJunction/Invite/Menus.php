<?php

namespace hypeJunction\Invite;

use ElggMenuItem;

class Menus {

	/**
	 * Setup page menu
	 *
	 * @param string         $hook   "register"
	 * @param string         $type   "menu:page"
	 * @param ElggMenuItem[] $return Menu
	 * @param array          $params Hook params
	 * @return array
	 */
	public static function setupPageMenu($hook, $type, $return, $params) {

		if (!elgg_in_context('friends')) {
			return;
		}

		$page_owner = elgg_get_page_owner_entity();

		$return[] = ElggMenuItem::factory([
			'name' => 'friends:invite',
			'href' => "friends/$page_owner->username/invite",
			'text' => elgg_echo('users:invite:invite'),
		]);

		return $return;
	}

}
