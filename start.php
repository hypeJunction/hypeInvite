<?php

/**
 * hypeInvite
 *
 * An interface for inviting new users to the site
 * 
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2016, Ismayil Khayredinov
 */
require_once __DIR__ . '/autoloader.php';

use hypeJunction\Invite\InviteService;
use hypeJunction\Invite\Menus;
use hypeJunction\Invite\Router;

elgg_register_event_handler('init', 'system', function() {

	elgg_register_plugin_hook_handler('route', 'friends', [Router::class, 'routeFriends']);
	elgg_register_plugin_hook_handler('route', 'groups', [Router::class, 'routeGroups']);

	elgg_register_plugin_hook_handler('register', 'menu:page', [Menus::class, 'setupPageMenu']);

	elgg_register_action('users/invite', __DIR__ . '/actions/users/invite.php');
	elgg_register_action('groups/invite', __DIR__ . '/actions/groups/invite.php');

	elgg_register_event_handler('create', 'user', [InviteService::class, 'acceptUserInvites']);
	elgg_register_plugin_hook_handler('accept', 'invite', [InviteService::class, 'addFriendships']);
	elgg_register_plugin_hook_handler('accept', 'invite', [InviteService::class, 'addMemberships']);
	
	elgg_extend_view('register/extend', 'forms/register/invitation_code', 100);
	elgg_register_plugin_hook_handler('action', 'register', [InviteService::class, 'registerActionGatekeeper'], 1);
	elgg_register_plugin_hook_handler('registration_link', 'site', [InviteService::class, 'generateRegistrationLink']);
	
	add_group_tool_option('invites', elgg_echo('groups:invite:tool_option'), false);

	elgg_register_plugin_hook_handler('get_templates', 'notifications', [InviteService::class, 'registerCustomTemplates']);

	elgg_extend_view('filters/friends', 'filters/users/invite', 100);
	elgg_extend_view('groups/profile/layout', 'groups/profile/buttons/invite');
});

