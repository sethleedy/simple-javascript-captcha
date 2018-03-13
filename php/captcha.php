<?php

// Working PHP versions here

// Debugging
error_reporting(E_ALL);

// Setup DB object
	require_once "autoloader.php"; // Loads the function code for autoloading classes

	// Your custom class dir
	define('CLASS_DIR', getcwd()."/class");

	// Add your class dir to include path
	$dePath = get_include_path().PATH_SEPARATOR.CLASS_DIR;
	//echo $dePath;
	set_include_path($dePath);

	// You can use this trick to make autoloader look for commonly used "My.class.php" type filenames
	//spl_autoload_extensions('.class.php');
	spl_autoload_extensions('.php');

	// Use default autoload implementation
	spl_autoload_register('loadClass');

// setting the access and configration to your database

$database = new Filebase\Database([
	'dir' => 'DB',
	'format'         => \Filebase\Format\Json::class,
	'cache'          => true,
	'cache_expires'  => 1800,
	'pretty'         => true,
	'safe_filename'  => true,
]);

	// // get the user information by id
	// $item = $db->get($userId);
	// get() returns \Filebase\Document object and has its own methods which you can call.

	// Method	Details
	// save()	Saves document in current state
	// delete()	Deletes current document (can not be undone)
	// toArray()	Array of items in document
	// getId()	Document Id
	// createdAt()	Document was created (default Y-m-d H:i:s)
	// updatedAt()	Document was updated (default Y-m-d H:i:s)
	// field()	You can also use . dot delimiter to find values from nested arrays
	// isCache()	(true/false) if the current document is loaded from cache
	// filter()	Refer to the Custom Filters

// Start using sessions
session_start();

// If session variable "check" passed, check the prev gen captcha to the passed value
if (isset($_POST['validate'])) { // Checking the inputted value for correctness

	// debugging
	echo $_SESSION["captcha"];
	
	// Pull out the captcha that we need to validate from the DB, using the SESSION ID.
	// Compare against POST validate variable.
		// should equal...
	$database->assertEquals('value', $val->key);
	
	// // get saved data (put into array)
	//$val = $db->get('test_save');

} else { // Making a new captcha
	
	// Store in Session variable for later use in comparing and using on the JS side.
	$_SESSION["captcha"] = gen_random_captcha();

	// Store in DB for later comparison, using the SESSION ID. ?Session id is immutable right ?

	// save data
	$sessionID = session_id();
	$captchaDB = $database->get($sessionID)->save(['captcha'=>$_SESSION["captcha"]]);

}

function gen_random_captcha() {
// Set session for all generated strings so we can check if the input was correct later.

	$seed = str_split('abcdefghijklmnopqrstuvwxyz'
					 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
					 .'0123456789'); // and any other characters
	shuffle($seed); // probably optional since array is randomized; this may be redundant
	$rand = ' ';

	// Loop, get the random chars and add spaces between them to the variable $rand.
	foreach (array_rand($seed, 6) as $k) $rand .= " " . $seed[$k];

	return $rand;	
}

?>