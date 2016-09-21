<?php

elgg_gatekeeper();

$username = elgg_extract('username', $vars);
$user = get_user_by_username($username);

if (!$user || !$user->canEdit()) {
	forward('', '403');
}

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('users:invite');

elgg_push_breadcrumb(elgg_echo('friends'), "friends");
elgg_push_breadcrumb($user->getDisplayName(), "friends/{$user->username}");
elgg_push_breadcrumb($title);

$filter = elgg_view('filters/friends', array(
	'filter_context' => 'invite',
	'entity' => $user,
		));

$content = elgg_view_form('users/invite', [], [
	'entity' => $user,
]);

$layout = elgg_view_layout('content', array(
	'title' => $title,
	'content' => $content,
	'filter' => $filter ? : false,
		));

echo elgg_view_page($title, $layout);
