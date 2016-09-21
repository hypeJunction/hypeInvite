<?php

use hypeJunction\Invite\Invite;
use hypeJunction\Invite\InviteService;

/**
 * Creates a new user invite
 *
 * @param string $email Email address
 * @return Invite
 */
function users_invite_create_user_invite($email) {
	return InviteService::createInvite($email);
}

/**
 * Returns an invite object
 *
 * @param string $email Email address
 * @return Invite|false
 */
function users_invite_get_user_invite($email) {
	return InviteService::getInvite($email);
}

/**
 * Returns a group invite object
 *
 * @param string $email Email address
 * @return ElggObject|false
 */
function groups_invite_get_group_invite($email) {
	return users_invite_get_user_invite($email);
}

/**
 * Creates a new group invite
 *
 * @param string $email Email address
 * @return ElggObject
 */
function groups_invite_create_group_invite($email) {
	return users_invite_create_user_invite($email);
}

/**
 * Generate a registration link
 *
 * @param string $email        Email address of the invitee
 * @param int    $inviter_guid GUID of the inviting user
 * @param string $default      Default registration URL
 * @return string
 */
function users_invite_get_registration_link($email, $inviter_guid = null, $default = 'register') {
	$url = elgg_trigger_plugin_hook('registration_link', 'site', [
			'email' => $email,
			'friend_guid' => $inviter_guid,
		], $default);

	return elgg_normalize_url($url);
}