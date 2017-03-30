<?php

/** This logic works only till dirnames and main php filenames are the same. */

// @formatter:off
foreach ( [
		'wp-tweaks',
		'pillanart-ms',
	] as $name ) {
	require_once __DIR__ . "/{$name}/{$name}.php";
}
// @formatter:on
