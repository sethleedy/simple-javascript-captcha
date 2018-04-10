<?php

	// Working PHP versions here

	// Debugging
	error_reporting(E_ALL);

	// Start using sessions
	// Use a custom Session Object
	session_start();

	// Import object to access Classes
	require_once "autoloader.php"; // Loads the function code for autoloading classes
	// Your custom class dir
	define('CLASS_DIR', getcwd()."/class"); // Within php directory

	// Add your class dir to include path
	$dePath = get_include_path().PATH_SEPARATOR.CLASS_DIR;
	//echo $dePath;
	set_include_path($dePath);

	// Set some PHP autoload settings
	// You can use this trick to make autoloader look for commonly used "My.class.php" type filenames
	//spl_autoload_extensions('.class.php');
	spl_autoload_extensions('.php');
	// Use default autoload implementation
	spl_autoload_register('loadClass');

	// Setup object to pass back to JS
	// Holds the captcha & running errorLog.
	$JSObject = new \stdClass();
	$JSObject->errLog = [];
	$JSObject->captcha = "";
	$JSObject->captchaReset = false;
	$JSObject->validating = false;


	// Load the captcha class I made.
	$captchaClass = new Captcha\doCaptcha();

	$JSObject->errLog[] = "Cookie: "+$_COOKIE['validate']; // Debug

	if (isset($_COOKIE['validate'])) { // Checking the inputted value for correctness
		
		$JSObject->validating = true;
		$JSObject->captcha = $captchaClass->doValidation();

	} else { // Making a new captcha
		
		$JSObject->captchaReset = true;
		$JSObject->captcha = $captchaClass->resetCaptcha();		
		
	}

	if ($_COOKIE['error'] == true) {
		$JSObject->errLog[] = $JSObject->captcha; // Record the error
		$JSObject->captcha = ""; // Clear the error from Captcha
		$_COOKIE['error'] == false; // Reset the error boolean.
	}
	
	// Send the JSObject back to the frontend.
	echo json_encode($JSObject);


?>