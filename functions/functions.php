<?php
/***********HELPER FUNCTIONS********/
function clean($string) {


	return htmlentities($string);
}

function redirect($location) {


	return header("Location: {$location}"); //{} b/c variables


}


function set_message($message) {


	if(!empty($message)) {
		$_SESSION['message'] = $message;
	}

	else {
		$message = "";
	}



}


function display_message() {
	if(isset($_SESSION['message'])) {
		echo $_SESSION['message'];

		unset($_SESSION['message']);
	}
}


function token_generator() {
	//Advanced stuff but USEFUL
	$token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));
	return $token;
}

/*******VALIDATION FUNCTIONS*******/


?>