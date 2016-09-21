<?php

require_once __DIR__ . '/autoloader.php';

use hypeJunction\Invite\Invite;

$subtypes = [
	Invite::class => Invite::SUBTYPE,
	'group_invite' => Invite::SUBTYPE, // BC
];

foreach ($subtypes as $subtype => $class) {
	if (!update_subtype('object', $subtype, $class)) {
		add_subtype('object', $subtype, $class);
	}
}

// Migrate all settings
$old_settings = [
	'users_invite' => [
		'invite_only_network' => 'invite_only_network',
	],
	'groups_invite' => [
		'require_confirmation' => 'groups_require_confirmation',
		'users_tab' => 'groups_users_tab',
		'emails_tab' => 'groups_emails_tab',
	],
];

foreach ($old_settings as $plugin => $setttings) {
	foreach ($settings as $old_key => $new_key) {
		if (is_null(elgg_get_plugin_setting($old_key, 'hypeInvite'))) {
			$value = elgg_get_plugin_setting($old, $plugin);
			elgg_set_plugin_setting($new_key, $value, 'hypeInvite');
		}
	}
}