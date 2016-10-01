<?php

namespace hypeJunction\Invite;

use ElggBatch;
use ElggUser;

/**
 * @access private
 */
class InviteService {

	/**
	 * Creates a new user invite
	 *
	 * @param string $email Email address
	 * @return Invite
	 */
	public static function createInvite($email) {
		$user_invite = users_invite_get_user_invite($email);
		if ($user_invite) {
			return $user_invite;
		}

		$ia = elgg_set_ignore_access(true);

		$site = elgg_get_site_entity();

		$user_invite = new Invite();
		$user_invite->subtype = 'user_invite';
		$user_invite->owner_guid = $site->guid;
		$user_invite->container_guid = $site->guid;
		$user_invite->access_id = ACCESS_PUBLIC;
		$user_invite->email = $email;

		$user_invite->invite_codes = self::generateInviteCode();

		$user_invite->save();

		elgg_set_ignore_access($ia);

		return $user_invite;
	}

	/**
	 * Returns an invite object
	 *
	 * @param string $email Email address
	 * @return Invite|false
	 */
	public static function getInvite($email) {

		$ia = elgg_set_ignore_access(true);

		$invites = elgg_get_entities_from_metadata(array(
			'types' => 'object',
			'subtypes' => 'user_invite',
			'metadata_name_value_pairs' => array(
				'name' => 'email',
				'value' => $email,
			),
			'limit' => 1,
		));

		elgg_set_ignore_access($ia);

		return $invites ? $invites[0] : false;
	}

	/**
	 * Returns an invite object
	 *
	 * @param string $invite_code Invite code
	 * @return Invite|false
	 */
	public static function getInviteByCode($invite_code) {

		$ia = elgg_set_ignore_access(true);

		$invites = elgg_get_entities_from_metadata(array(
			'types' => 'object',
			'subtypes' => 'user_invite',
			'metadata_name_value_pairs' => array(
				'name' => 'invite_codes',
				'value' => $invite_code,
			),
			'limit' => 1,
		));

		elgg_set_ignore_access($ia);

		return $invites ? $invites[0] : false;
	}

	/**
	 *
	 * @return stringGenerate a unique invite code
	 * @return string
	 */
	public static function generateInviteCode() {
		$invite_code = generate_random_cleartext_password();
		while (self::getInviteByCode($invite_code)) {
			$invite_code = generate_random_cleartext_password();
		}
		return $invite_code;
	}

	/**
	 * Validate invite code
	 *
	 * @param string $email       Email address
	 * @param string $invite_code Invitation code
	 * @return bool
	 */
	public static function validateInviteCode($email, $invite_code) {

		$invitation_codes = elgg_get_plugin_setting('invitation_codes', 'hypeInvite');
		if ($invitation_codes) {
			$invitation_codes = explode(PHP_EOL, $invitation_codes);
			array_walk($invitation_codes, 'trim');
			if (in_array($invite_code, $invitation_codes)) {
				return true;
			}
		}

		$invites = elgg_get_entities_from_metadata(array(
			'types' => 'object',
			'subtypes' => 'user_invite',
			'metadata_name_value_pairs' => array(
				array(
					'name' => 'email',
					'value' => $email,
				),
				array(
					'name' => 'invite_codes',
					'value' => $invite_code,
				)
			),
			'count' => true,
		));

		return ($invites);
	}

	/**
	 * Generate registration link
	 *
	 * @param string $hook   "registration_link"
	 * @param string $type   "site"
	 * @param string $return Link
	 * @param string $params Hook params
	 * @uses $params['email']
	 * @return string
	 */
	public static function generateRegistrationLink($hook, $type, $return, $params) {

		if (!$return) {
			$return = elgg_normalize_url('register');
		}

		$email = elgg_extract('email', $params);
		if (!$email) {
			return $return;
		}

		$user_invite = users_invite_get_user_invite($email);
		if (!$user_invite) {
			$user_invite = users_invite_create_user_invite($email);
		}

		$friend_guid = elgg_extract('friend_guid', $params);
		if ($friend_guid) {
			add_entity_relationship($user_invite->guid, 'invited_by', $friend_guid);
		}

		if (!$user_invite->invite_codes) {
			$invite_code = generate_random_cleartext_password();
			while ($this->getInviteByCode($invite_code)) {
				$invite_code = generate_random_cleartext_password();
			}
			$user_invite->invite_codes = $invite_code;
		}

		$time = time();

		$invite_codes = (array) $user_invite->invite_codes;

		return elgg_http_add_url_query_elements($return, [
			'e' => $email,
			'ts' => $time,
			'friend_guid' => $friend_guid,
			'invitecode' => $invite_codes[0],
		]);
	}

	/**
	 * Validate required invitation code
	 *
	 * @param string $hook   "action"
	 * @param string $type   "register"
	 * @param bool   $return Proceed with action?
	 * @param array  $params Hook params
	 * @return void
	 */
	public static function registerActionGatekeeper($hook, $type, $return, $params) {

		if (!elgg_get_plugin_setting('invite_only_network', 'hypeInvite')) {
			return;
		}

		$email = get_input('email');
		$code = get_input('invitation_code');

		if (!self::validateInviteCode($email, $code)) {
			elgg_make_sticky_form('register');
			register_error(elgg_echo('users:invite:invitation_code:mismatch'));
			forward(REFERRER);
		}
	}

	/**
	 * Accept invites when user is created
	 *
	 * @param string   $event "create"
	 * @param string   $type  "user"
	 * @param ElggUser $user  User entity
	 * @return void
	 */
	public static function acceptUserInvites($event, $type, $user) {

		$email = $user->email;
		$code = get_input('invitation_code');

		if (self::validateInviteCode($email, $code)) {
			// Consider user email validated when joined via invitation email
			elgg_set_user_validation_status($user->guid, true, 'invitation_code');
		}

		$ia = elgg_set_ignore_access(true);

		$invites = new ElggBatch('elgg_get_entities_from_metadata', [
			'types' => 'object',
			'subtypes' => ['user_invite', 'group_invite'],
			'metadata_name_value_pairs' => array(
				'name' => 'email',
				'value' => $email,
			),
			'limit' => 0,
		]);

		$invites->setIncrementOffset(false);

		elgg_set_ignore_access($ia);

		foreach ($invites as $invite) {
			$params = [
				'invite' => $invite,
				'user' => $user,
			];
			if (elgg_trigger_plugin_hook('accept', 'invite', $params, true)) {
				$invite->delete();
			}
		}
	}

	/**
	 * Create friendships or friendship requests when the invite is accepted
	 * 
	 * @param string $hook   "accept"
	 * @param string $type   "invite"
	 * @param mixed  $return Prevent invite from being processed further
	 * @param array  $params Hook params
	 * @return void
	 */
	public static function addFriendships($hook, $type, $return, $params) {
		if ($return === false) {
			return;
		}

		$invite = elgg_extract('invite', $params);
		$user = elgg_extract('user', $params);

		$ia = elgg_set_ignore_access(true);

		$inviters = new ElggBatch('elgg_get_entities_from_relationship', array(
			'types' => 'user',
			'relationship' => 'invited_by',
			'relationship_guid' => (int) $invite->guid,
			'inverse_relationship' => false,
			'limit' => 0,
		));

		$accept_on_register = elgg_get_plugin_setting('friends_accept_on_register', 'hypeInvite');

		// We will respect friend_request setting for river events
		$add_to_river = true;
		$relationship = 'friend';
		if (elgg_is_active_plugin('friend_request')) {
			$add_to_river = elgg_get_plugin_setting('add_river', 'friend_request') !== 'no';
			$relationship = 'friendrequest';
		}

		$ref = get_input('ref');
		
		foreach ($inviters as $inviter) {
			/* @var $inviter ElggUser */

			if ($inviter->isFriendsWith($user->guid)) {
				continue;
			}
			
			if ($accept_on_register || $ref == $user->guid) {
				$inviter->addFriend($user->guid, $add_to_river);
				$user->addFriend($inviter->guid, $add_to_river);
			} else {
				add_entity_relationship($inviter->guid, $relationship, $user->guid);
			}
		}
		
		elgg_set_ignore_access($ia);
	}

	/**
	 * Create group invites when the invite is accepted
	 *
	 * @param string $hook   "accept"
	 * @param string $type   "invite"
	 * @param mixed  $return Prevent invite from being processed further
	 * @param array  $params Hook params
	 * @return void
	 */
	public static function addMemberships($hook, $type, $return, $params) {
		if ($return === false) {
			return;
		}

		$invite = elgg_extract('invite', $params);
		$user = elgg_extract('user', $params);

		$ia = elgg_set_ignore_access(true);

		$groups = new ElggBatch('elgg_get_entities_from_relationship', array(
			'types' => 'group',
			'relationship' => 'invited_to',
			'relationship_guid' => (int) $invite->guid,
			'inverse_relationship' => false,
			'limit' => 0,
		));

		$ref = get_input('ref');
		$accept_on_register = elgg_get_plugin_setting('groups_accept_on_register', 'hypeInvite');
		foreach ($groups as $group) {
			add_entity_relationship($group->guid, 'invited', $user->guid);

			if (is_callable('\AU\SubGroups\get_parent_group')) {
				// AU Subgroups is unable to resolve invites properly
				// unless we also invite the user to all parent groups
				$parent = $group;
				while($parent = \AU\SubGroups\get_parent_group($parent)) {
					add_entity_relationship($parent->guid, 'invited', $user->guid);
				}
			}

			if (is_callable('groups_join_group') && ($accept_on_register || $ref == $group->guid)) {
				groups_join_group($group, $user);
			}
		}

		elgg_set_ignore_access($ia);
	}

	/**
	 * Add instant notificaiton actions to the editable templates
	 *
	 * @param string $hook   "get_templates"
	 * @param string $type   "notifications"
	 * @param string $return Template names
	 * @param array  $params Hook params
	 * @return array
	 */
	function registerCustomTemplates($hook, $type, $return, $params) {
		$return[] = "groups_invite_user";
		return $return;
	}

	/**
	 * Callback function to search users
	 *
	 * @param string $term Query term
	 * @param array $options An array of getter options
	 * @return array An array of elgg entities matching the search criteria
	 */
	public static function searchUsersForGroupsInvite($term, $options = array()) {

		$options['query'] = $term;

		$guid = (int) get_input('group_guid');
		$dbprefix = elgg_get_config('dbprefix');

		$options['wheres']['non_member'] = "
					NOT EXISTS (SELECT 1 FROM {$dbprefix}entity_relationships
						WHERE guid_one = e.guid
							AND relationship = 'member'
							AND guid_two = $guid)
					";

		if (get_input('friends_only', true)) {
			$user_guid = (int) elgg_get_logged_in_user_guid();
			$options['wheres']['friend'] = "
					EXISTS (SELECT 1 FROM {$dbprefix}entity_relationships
						WHERE guid_one = $user_guid
							AND relationship = 'friend'
							AND guid_two = e.guid)
					";
		}
		
		$results = elgg_trigger_plugin_hook('search', 'user', $options, array());
		return elgg_extract('entities', $results, array());
	}

}
