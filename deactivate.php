<?php

use hypeJunction\Invite\Invite;

$subtypes = array(Invite::SUBTYPE);

foreach ($subtypes as $subtype) {
	update_subtype('object', $subtype);
}