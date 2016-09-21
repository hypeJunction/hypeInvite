<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view_input('plaintext', array(
	'name' => 'emails',
	'label' => elgg_echo('groups:invite:emails:select'),
	'help' => elgg_echo('groups:invite:emails:select:help'),
	'rows' => 3,
));