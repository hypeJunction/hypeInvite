<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_input('plaintext', array(
	'name' => 'emails',
	'label' => elgg_echo('users:invite:emails:select'),
	'help' => elgg_echo('users:invite:emails:select:help'),
	'rows' => 3,
));

echo elgg_view_input('plaintext', array(
	'name' => 'message',
	'label' => elgg_echo('users:invite:message'),
	'rows' => 3,
));

$chbkx = elgg_format_element('input', array(
	'type' => 'checkbox',
	'name' => 'resend',
	'default' => false,
		));
$input = elgg_format_element('label', [], $chbkx . elgg_echo('users:invite:resend'));
echo elgg_view('elements/forms/field', array(
	'input' => $input,
));

echo elgg_view_input('hidden', array(
	'name' => 'guid',
	'value' => $entity->guid,
));

echo elgg_view_input('submit', array(
	'value' => elgg_echo('users:invite'),
	'field_class' => 'elgg-foot',
));

