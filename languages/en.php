<?php

return [

	'users:invite' => 'Invite',
	'users:invite:invite' => 'Invite users',
	'users:invite:emails:select' => 'Emails to invite',
	'users:invite:emails:select:help' => 'Enter one email per line',
	'users:invite:message' => 'Message to include in the invitation',
	'users:invite:resend' => 'Resend invitations to previously invited emails',
	'users:invite:notify:subject' => 'You are invited to join %s',
	'users:invite:notify:body' => '%1$s has invited you to join %2$s.

		%3$s
		Please visit the following link to create an account:
		%4$s

		%6$s
		',
	'users:invite:notify:message' => '
		They have included the following message for you:
		%s

		',

	'users:invite:notify:invite_code' => '
		Please use the following invitation code:
		%1$s
		',

	'users:invite:settings:invite_only_network' => 'Invite Only Registration',
	'users:invite:settings:invite_only_network:help' => 'If enabled, only users with a valid invitation code will be allowed to register',
	'users:invite:settings:invite_code_register_form' => 'Invite Code Field',
	'users:invite:settings:invite_code_register_form:help' => 'If disabled, the invitation code field won\'t be shown on the registration form',
	'users:invite:settings:invitation_codes' => 'Invitation codes',
	'users:invite:settings:invitation_codes:help' => 'Please list site-wide invitation codes (one per line) that can be used by any user to register',
	'users:invite:settings:friends_accept_on_register' => 'Automatically accept off-site friend requests',
	'users:invite:settings:friends_accept_on_register:help' => '
		This plugin keeps tracks of all invites ever sent to the same email address.
		If enabled, this feature will automatically accept all friend requests ever sent to the registering user\'s email address.
		Otherwise, only the request that was clicked on in a single email notification will be accepted.
	',

	'users:invite:result:invited' => '%s of %s invitations were successfully sent',
	'users:invite:result:skipped' => '%s of %s invitations were skipped, because users have already been invited or have an account',
	'users:invite:result:error' => '%s of %s invitations could not be sent due to errors',

	'users:invite:invitation_code' => 'Invitation Code',
	'users:invite:invitation_code:mismatch' => 'The invitation code you have provided is not valid',

	'groups:invite:settings:require_confirmation' => 'Groups: Require confirmation from invitees',
	'groups:invite:settings:require_confirmation:help' => 'Invited users must always accept invitation. When enabled, this feature will prevent group admins from adding users to the group without invitation',
	'groups:invite:settings:users_tab' => 'Groups: Allow any registered user to be invited',
	'groups:invite:settings:users_tab:help' => 'If enabled, users will be able to find and invite any registered user to a group. If disabled, only friends can be invited',
	'groups:invite:settings:emails_tab' => 'Groups: Allow invitation by email',
	'groups:invite:settings:emails_tab:help' => 'If enabled, users will be able to invite other people to the group via email',
	'groups:invite:settings:groups_accept_on_register' => 'Groups: Automatically accept off-site group invitations',
	'groups:invite:settings:groups_accept_on_register:help' => '
		This plugin keeps tracks of all invites ever sent to the same email address.
		If enabled, this feature will automatically accept all group invitations ever sent to registering user\'s email address.
		Otherwise, only the request that was clicked on in a single email notification will be accepted.
	',

	'groups:invite:friends' => 'Friends',
	'groups:invite:users' => 'Users',
	'groups:invite:emails' => 'Emails',
	'groups:invite:friends:select' => 'Friends to invite',
	'groups:invite:users:select' => 'Users to invite',
	'groups:invite:emails:select' => 'Emails to invite',
	'groups:invite:emails:select:help' => 'Enter one email per line',
	'groups:invite:message' => 'Message to include in the invitation',

	'groups:invite:resend' => 'Resend invitations to previously invited members',
	'groups:invite:action:invite' => 'Send invitation to become a member',
	'groups:invite:action:add' => 'Add as member without invitation',

	'groups:invite' => 'Invite',
	'groups:invite:title' => 'Invite members to this group',
	'groups:inviteto' => "Invite members to '%s'",

	'groups:invite:tool_option' => 'Allows members to invite other members',
	'groups:invite:not_found' => 'Group not found',

	'groups:invite:notify:subject' => 'You are invited to join %s',
	'groups:invite:notify:body' => '%1$s invites you to join %2$s at %3$s.

		%4$s
		Please visit the following link to create an account:
		%5$s
		
		%7$s
		',
	'groups:invite:notify:message' => '
		They have included the following message for you:
		%s

		',

	'groups:invite:notify:message' => '
		Please use the following invitation code:
		%1$s
		
		',

	'groups:invite:user:subject' => "%s invites you to join %s",
	'groups:invite:user:body' => "Hi %s,

%s invites you to join '%s'. Click below to confirm the invitation:

%s",

	'groups:invite:result:invited' => '%s of %s invitations were successfully sent',
	'groups:invite:result:skipped' => '%s of %s invitations were skipped, because users have already been invited',
	'groups:invite:result:added' => '%s of %s users were added as group members',
	'groups:invite:result:error' => '%s of %s invitations could not be sent due to errors',

	'groups:invite:confirm:error' => 'Your request can not be completed. Please login and confirm the invitation manually',

	'notification:invite' => 'Notification sent when a non-registered user is invited to join the site',
	'notification:groups_invite_user' => 'Notification sent when a non-registered user is invited to join a group',
	'notification:groups_invite_member' => 'Notification sent when a registered user is invited to join a group',
];
