<?php

namespace hypeJunction\Invite;

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

}
