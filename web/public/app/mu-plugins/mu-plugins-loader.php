<?php

foreach ([
		'wp-tweaks',
		'pillanart-ms'
		] as $name ) {
	require_once __DIR__ . "/{$name}/{$name}.php";
}
