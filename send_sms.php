<?php

	/**
	 * @author  Lars Erik Storbukås
	 * @mail    larserik@virtualgeek.no
	 * @date    17/03 - 2015
	 * @title   Talkmore SMS Sender
	 *
	 **/

	function send_sms ($username, $password, $number, $message){
		$loginURL = 'https://www.talkmore.no/talkmore3/servlet/Login';
		$destinationURL = 'https://www.talkmore.no/talkmore3/servlet/SendSmsFromSelfcare';

		if(!valid_params($message, $number) { return; } // abort, not valid
		
		// initialize curl
		$ch = curl_init();

		// Set the URL to work with
		curl_setopt($ch, CURLOPT_URL, $loginURL);

		// ENABLE HTTP POST
		curl_setopt($ch, CURLOPT_POST, 1);

		// Set the post parameters
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'username='.$username.'&password='.$password);

		// Handle cookies for the login
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

		// Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		// not to print out the results of its query.
		// Instead, it will return the results as a string return value
		// from curl_exec() instead of the usual true/false.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// execute the request (the login)
		$store = curl_exec($ch);

		// the login is now done and you can continue to get the
		// protected content.

		// set the URL to the protected file / destination URL
		curl_setopt($ch, CURLOPT_URL, $destinationURL);

		//Set the post parameters ------
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'message1='.$message.$additional.'&list='.$number);

		//execute the request
		$content = curl_exec($ch);

		$message = preg_replace('/\\r?\\n|\\r/','[ENTER]', $message);		

		// log the sending of SMS, since Talkmore doesn't save sent text messages
		file_put_contents('log.txt', $number." | ".$message."\n", FILE_APPEND | LOCK_EX);
	}

	function valid_params ($message, $number) {
		return (($message != "") && (strlen($message) == 8))
	}
	
?>
