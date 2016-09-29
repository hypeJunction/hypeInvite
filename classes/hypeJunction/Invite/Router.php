<?php

namespace hypeJunction\Invite;

use ElggGroup;
use ElggUser;

class Router {

	/**
	 * Route friends page
	 *
	 * @param string $hook   "route"
	 * @param string $type   "friends"
	 * @param mixed  $return Route details
	 * @param array  $params Hook params
	 * @return mixed
	 */
	public static function routeFriends($hook, $type, $return, $params) {

		if (!is_array($return)) {
			return;
		}

		$identifier = elgg_extract('identifier', $return);
		$segments = (array) elgg_extract('segments', $return, []);

		if ($identifier != 'friends') {
			return;
		}

		$username = array_shift($segments);
		$page = array_shift($segments);

		switch ($page) {
			case 'invite' :
				echo elgg_view_resource('friends/invite', [
					'username' => $username,
				]);
				return false;
		}
	}

	/**
	 * Routes group invitation confirmation page
	 *
	 * @param string $hook   "route"
	 * @param string $type   "groups"
	 * @param array  $return Identifier and segments
	 * @param array  $params Hook params
	 * @return array
	 */
	public static function routeGroups($hook, $type, $return, $params) {

		$identifier = $return['identifier'];
		$segments = $return['segments'];

		if ($identifier == 'groups' && $segments[0] == 'invitations' && $segments[1] == 'confirm') {
			$i = (int) get_input('i');
			$g = (int) get_input('g');
			$hmac = elgg_build_hmac(array(
				'i' => $i,
				'g' => $g,
			));
			if (!$hmac->matchesToken(get_input('m'))) {
				register_error(elgg_echo('groups:invite:confirm:error'));
				forward('', '403');
			}

			$ia = elgg_set_ignore_access(true);
			$user = get_entity($i);
			$group = get_entity($g);

			if (groups_join_group($group, $user)) {
				system_message(elgg_echo('groups:joined'));
			} else {
				register_error(elgg_echo('groups:invite:confirm:error'));
			}
			forward('');
		}
	}

	/**
	 * Hijack forward URL of the register action
	 *
	 * @param string $hook   "register"
	 * @param string $type   "user"
	 * @param bool   $return Proceed with registration
	 * @param array  $params Hook params
	 * @return bool
	 */
	public static function hijackForwardURL($hook, $type, $return, $params) {
		if ($return === false) {
			return;
		}
		
		$user = elgg_extract('user', $params);
		/* @var $user ElggUser */

		$ia = elgg_set_ignore_access(true);

		if ($user->isEnabled()) {
			$forward_url = '';

			$ref = get_input('ref');
			$entity = get_entity($ref);

			if ($entity instanceof ElggGroup) {
				if (elgg_get_plugin_setting('groups_accept_on_register', 'hypeInvite')) {
					$forward_url = $entity->getURL();
				} else if (elgg_is_active_plugin('groups')) {
					$forward_url = elgg_normalize_url("groups/invitations/$user->username");
				}
			} else if ($entity instanceof ElggUser) {
				if (elgg_get_plugin_setting('friends_accept_on_register', 'hypeInvite')) {
					$forward_url = $entity->getURL();
				} else if (elgg_is_active_plugin('friend_request')) {
					$forward_url = elgg_normalize_url("friend_request/$user->username/received");
				}
			}

			elgg_register_plugin_hook_handler('forward', 'all', function() use ($forward_url) {
				if ($forward_url) {
					return $forward_url;
				}
			});
		}

		elgg_set_ignore_access($ia);
	}

}
