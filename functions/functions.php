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

function validate_user_registration() {

	$errors = [];
	$min = 3;
	$max = 20;

	if($_SERVER['REQUEST_METHOD'] == "POST") {

		//Clean is helper function defined above
		$first_name 	= clean($_POST['first_name']); 
		$last_name 		= clean($_POST['last_name']);
		$username 		= clean($_POST['username']);
		$email 			= clean($_POST['email']);
		$password	 	= clean($_POST['password']);
		$confirm_password	 	= clean($_POST['confirm_password']);

	
		if(strlen($first_name) < $min) {
			
			$errors[] = "Your first name cannot be less than {$min} characters";
		}

		if(strlen($first_name) > $max) {
			
			$errors[] = "Your first name cannot be greater than {$max} characters";
		}


		//Not doing for empty firstname b/c HTML5 has it.

		if(strlen($last_name) < $min) {
			
			$errors[] = "Your last name cannot be less than {$min} characters";
		}

		if(strlen($last_name) > $max) {
			
			$errors[] = "Your last name cannot be legreaterss than {$max} characters";
		}

		if(!empty($errors)) {
			foreach ($errors as $error) {
				# code...

$message = <<<DELIMITER

<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Warning!</strong> $error
</div>
DELIMITER; //MUST BE NO SPACE IN THE BEGINNIGN OF DELIMITER
echo $message;
			}
		} 

	}


}

?>