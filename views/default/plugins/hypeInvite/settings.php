<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_input('select', array(
	'name' => 'params[invite_only_network]',
	'value' => $entity->invite_only_network,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'label' => elgg_echo('users:invite:settings:invite_only_network'),
	'help' => elgg_echo('users:invite:settings:invite_only_network:help'),
));

echo elgg_view_input('select', array(
	'name' => 'params[invite_code_register_form]',
	'value' => isset($entity->invite_code_register_form) ? $entity->invite_code_register_form : 1,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'label' => elgg_echo('users:invite:settings:invite_code_register_form'),
	'help' => elgg_echo('users:invite:settings:invite_code_register_form:help'),
));

echo elgg_view_input('plaintext', array(
	'name' => 'params[invitation_codes]',
	'value' => $entity->invitation_codes,
	'label' => elgg_echo('users:invite:settings:invitation_codes'),
	'help' => elgg_echo('users:invite:settings:invitation_codes:help'),
));

echo elgg_view_input('select', array(
	'name' => 'params[groups_require_confirmation]',
	'value' => $entity->groups_require_confirmation,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'label' => elgg_echo('groups:invite:settings:require_confirmation'),
	'help' => elgg_echo('groups:invite:settings:require_confirmation:help'),
));

echo elgg_view_input('select', array(
	'name' => 'params[groups_users_tab]',
	'value' => $entity->groups_users_tab,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'label' => elgg_echo('groups:invite:settings:users_tab'),
	'help' => elgg_echo('groups:invite:settings:users_tab:help'),
));

echo elgg_view_input('select', array(
	'name' => 'params[groups_emails_tab]',
	'value' => $entity->groups_emails_tab,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'label' => elgg_echo('groups:invite:settings:emails_tab'),
	'help' => elgg_echo('groups:invite:settings:emails_tab:help'),
));

echo elgg_view_input('select', array(
	'name' => 'params[friends_accept_on_register]',
	'value' => $entity->friends_accept_on_register,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'label' => elgg_echo('users:invite:settings:friends_accept_on_register'),
	'help' => elgg_echo('users:invite:settings:friends_accept_on_register:help'),
));

echo elgg_view_input('select', array(
	'name' => 'params[groups_accept_on_register]',
	'value' => $entity->groups_accept_on_register,
	'options_values' => array(
		0 => elgg_echo('option:no'),
		1 => elgg_echo('option:yes'),
	),
	'label' => elgg_echo('groups:invite:settings:groups_accept_on_register'),
	'help' => elgg_echo('groups:invite:settings:groups_accept_on_register:help'),
));