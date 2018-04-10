<?php namespace Captcha;

class doCaptcha {

	protected $capObject;

	public function __construct() {
			
			$_COOKIE['error'] = false;

			$this->capObject = new \stdClass();
			$this->capObject->sessionID = session_id();

			// setting the access and configration to your database
			$this->capObject->database = new \Filebase\Database([ // Add the database to the capObject
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

	}

	public function doValidation() {
			
			// debugging
			//echo $_SESSION["validate"];
			
			// Pull out the captcha that we need to validate from the DB, using the SESSION ID.
			// Compare against POST validate variable.
				// should equal...
			//$database->assertEquals($_COOKIE['validate'], $sessionID->key);
			
			// // get saved data (put into array)
			$sessID=$this->capObject->sessionID;
			$val = $this->capObject->database->get($this->capObject->sessionID);

			// If the passed captcha matches the saved captcha, then all's good.
			if ($_COOKIE['validate'] == $val->$sessID) { // Matches without spaces

				$_COOKIE['validate'] == "";
				return $val->$sessID;

			} else {
				//echo "error";
				$_COOKIE['error'] = true;
				
				return $this->resetCaptcha();
			}

	}

	public function resetCaptcha() {

		// Store in Session variable for later use in comparing and using on the JS side.
		$_SESSION["captcha"] = $this->gen_random_captcha();

		// Store in DB for later comparison, using the SESSION ID. ?Session id is immutable right ?

		// save data
		// Save the captcha without spaces..
		$noSpacesCaptcha = str_replace(' ', '', $_SESSION["captcha"]);
		$captchaDB = $this->capObject->database->get($this->capObject->sessionID)->save([$this->capObject->sessionID=>$noSpacesCaptcha]);

		// Echo out the result, with spaces and other deterrents, for JS to get with its AJAX call
		//$formatted = implode(' ',str_split($noSpacesCaptcha)); 
		//echo $_SESSION["captcha"]; // Default, with spaces..
		return $_SESSION["captcha"]; // Default, with spaces..

	}

	protected function gen_random_captcha() {
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
}
?>