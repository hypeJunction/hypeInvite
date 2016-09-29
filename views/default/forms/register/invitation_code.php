<?php

echo elgg_view_input('text', [
	'name' => 'invitation_code',
	'value' => elgg_extract('invitecode', $vars, get_input('invitecode')),
	'label' => elgg_echo('users:invite:invitation_code'),
	'required' => elgg_get_plugin_setting('invite_only_network', 'hypeInvite'),
]);

// Referring entity (group or user)
// Will be used to determine forward URL upon registration
echo elgg_view_input('hidden', [
	'name' => 'ref',
	'value' => elgg_extract('ref', $vars, get_input('ref')),
]);