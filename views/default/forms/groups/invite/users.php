<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_input('tokeninput', array(
	'name' => 'invitee_guids',
	'label' => elgg_echo('groups:invite:users:select'),
	'multiple' => true,
	'callback' => '\\hypeJunction\\Invite\\InviteService::searchUsersForGroupsInvite',
	'query' => [
		'group_guid' => $entity->guid,
		'friends_only' => false,
	],
));