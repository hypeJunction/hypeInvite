<?php

$group = elgg_extract('entity', $vars);
if (!$group instanceof ElggGroup) {
	return;
}

if ($group->isMember() && $group->invites_enable == 'yes') {
	elgg_register_menu_item('title', array(
		'name' => 'groups:invite',
		'href' => "groups/invite/$group->guid",
		'text' => elgg_echo('groups:invite'),
		'link_class' => 'elgg-button elgg-button-action',
	));
}

