<?php

$entity = elgg_extract('entity', $vars);

$tabs = array();
$forms = '';

if (elgg_get_plugin_setting('groups_users_tab', 'hypeInvite', false)) {
	$tabs['users'] = array(
		'text' => elgg_echo('groups:invite:users'),
		'href' => '#groups-invite-users',
		'selected' => true,
	);

	$form = elgg_view('forms/groups/invite/users', $vars);
	$forms .= elgg_format_element('div', ['id' => 'groups-invite-users'], $form);
} else {
	$tabs['friends'] = array(
		'text' => elgg_echo('groups:invite:friends'),
		'href' => '#groups-invite-friends',
		'selected' => true,
	);
	$form = elgg_view('forms/groups/invite/friends', $vars);
	$forms .= elgg_format_element('div', ['id' => 'groups-invite-friends'], $form);
}

if (elgg_get_plugin_setting('groups_emails_tab', 'hypeInvite', false) && elgg_get_config('allow_registration')) {
	$tabs['emails'] = array(
		'text' => elgg_echo('groups:invite:emails'),
		'href' => '#groups-invite-emails',
	);
	$form = elgg_view('forms/groups/invite/emails', $vars);
	$forms .= elgg_format_element('div', [
		'id' => 'groups-invite-emails',
		'class' => 'hidden',
			], $form);
}

$tabs = elgg_view('navigation/tabs', array(
	'tabs' => $tabs,
		));

echo elgg_view('components/tabs', array(
	'id' => 'groups-invite',
	'tabs' => $tabs,
	'content' => $forms,
));

echo elgg_view_input('plaintext', array(
	'name' => 'message',
	'label' => elgg_echo('groups:invite:message'),
	'rows' => 3,
));

if ($entity->canEdit()) {
	$chbkx = elgg_format_element('input', array(
		'type' => 'checkbox',
		'name' => 'resend',
		'default' => false,
	));
	$input = elgg_format_element('label', [], $chbkx . elgg_echo('groups:invite:resend'));
	echo elgg_view('elements/forms/field', array(
		'input' => $input,
	));
}

if ($entity->canEdit() && (!elgg_get_plugin_setting('groups_require_confirmation', 'hypeInvite') || elgg_is_admin_logged_in())) {
	echo elgg_view_input('radio', array(
		'name' => 'invite_action',
		'value' => 'invite',
		'options' => array(
			elgg_echo('groups:invite:action:invite') => 'invite',
			elgg_echo('groups:invite:action:add') => 'add'
		),
	));
}

echo elgg_view_input('hidden', array(
	'name' => 'guid',
	'value' => $entity->guid,
));

echo elgg_view_input('submit', array(
	'value' => elgg_echo('groups:invite'),
	'field_class' => 'elgg-foot',
));

