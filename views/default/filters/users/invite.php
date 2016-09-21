<?php

// Extension for user_friends plugin filter

$entity = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
$filter_context = elgg_extract('filter_context', $vars, 'index');

$tabs = [];

if ($entity->canEdit()) {
	$tabs['invite'] = "friends/$entity->username/invite";
}

foreach ($tabs as $tab => $url) {
	elgg_register_menu_item('filter', array(
		'name' => "user:friends:$tab",
		'text' => elgg_echo("users:invite:$tab"),
		'href' => elgg_normalize_url($url),
		'selected' => $tab == $filter_context,
		'priority' => 800,
	));
}

