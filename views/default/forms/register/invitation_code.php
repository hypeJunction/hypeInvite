<?php

echo elgg_view_input('text', [
	'name' => 'invitation_code',
	'value' => elgg_extract('invitecode', $vars, get_input('invitecode')),
	'label' => elgg_echo('users:invite:invitation_code'),
	'required' => elgg_get_plugin_setting('invite_only_network', 'hypeInvite'),
]);