<?php

// PHP version here

// If session passed, expect also the string to check if correct.

// Set session for all generated strings so we can check if the input was correct later.

	$seed = str_split('abcdefghijklmnopqrstuvwxyz'
					 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
					 .'0123456789'); // and any other characters
	shuffle($seed); // probably optional since array_is randomized; this may be redundant
	$rand = ' ';

	// Loop, get the random chars and add spaces between them to the variable $rand.
	foreach (array_rand($seed, 6) as $k) $rand .= " " . $seed[$k];

	echo $rand;

?>