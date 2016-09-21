Invitations for Elgg
=========================
![Elgg 2.2](https://img.shields.io/badge/Elgg-2.2-orange.svg?style=flat-square)

## Features

 * Allows users to invite new users by email
 * An option to create an invite-only network
 * Keeps track of all invitations to the same email address
 * Creates friend requests when invitations are accepted
 * Group owners can allow members to invite other members
 * Site admins can allow group invitations of non-friends
 * Site admins can allow group invitations by email

## Notes

 * Registration must be enabled on the site for this plugin to work
 * In an invite-only network, uservalidationbyemail will be bypassed,
   as it is assumed that users would have received their invitation code by email
 * When invited by email to group, non-existing users will first have to create an account. Upon registration,
   invitations will be created for every group the email has been invited to before registration.

## Developer Notes

### Creating Invites

Other plugins may centralize off-site invitations and attach custom behavior to the invites.
For example, to invite non-registered users to an event by their email:

```php

$invite = users_invite_create_user_invite($email);
add_entity_relationship($invite->guid, 'invited_to', $event->guid);
add_entity_relationship($invite->guid, 'invited_by', $inviter->guid);

// generate a registration link to include in the notification
$registration_link = users_invite_get_registration_link($email, $inviter->guid);


// implement a custom handler
elgg_register_plugin_hook_handler('accept', 'invite', function($hook, $type, $return, $params) {

	$invite = $params['invite'];
	$user = $params['user'];

	$events = elgg_get_entities_from_relationship([
        'types' => 'object',
        'subtypes' => 'event',
		'relationship' => 'invited_to',
		'relationship_guid' => $invite->guid,
		'limit' => 0,
	]);

	if (!$events) {
		return;
	}

	foreach ($events as $event) {
		add_entity_relationship($user->guid, 'attending', $event->guid);
	}
});
```
