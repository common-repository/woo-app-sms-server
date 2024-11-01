<?php

/*
*	Plugin main config file
*/

$cfg = array(
	'server' => 'simple-sms.rscgrup.ro'
);

if($cfg['server']) {
	$cfg['server_url'] = 'https://' . $cfg['server'] . '/';
}