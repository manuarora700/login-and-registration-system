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

function validation_errors($error_message) {

$error_message = <<<DELIMITER

<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Warning!</strong> $error_message
</div>
DELIMITER;

return $error_message;

}

function email_exists($email) {

	$sql = "SELECT id FROM users WHERE email = '$email'";
	$result = query($sql);
	if(row_count($result) == 1) {
		return true;
	} else {
		return false;
	}

}

function username_exists($username) {

	$sql = "SELECT id FROM users WHERE username = '$username'";
	$result = query($sql);
	if(row_count($result) == 1) {
		return true;
	} else {
		return false;
	}

}


function send_email($email, $subject, $msg, $headers) {

	return mail($email, $subject, $msg, $headers);

	



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

		if(strlen($username) < $min) {
			
			$errors[] = "Your username cannot be lesser than {$min} characters";
		}

		if(strlen($username) > $max) {
			
			$errors[] = "Your username cannot be greater than {$max} characters";
		}

		if(username_exists($username)) {

				$errors[] = "Sorry, That username is already registered";


		}
		if(email_exists($email)) {

				$errors[] = "Sorry, That email is already registered";


		}
		if(strlen($email) > $max) {
			
			$errors[] = "Your email  cannot be legreaterss than {$max} characters";
		}
		if($password !== $confirm_password) {
			$errors[] = "Passwords donot match";
		}
		

		if(!empty($errors)) {
			foreach ($errors as $error) {
				# code...

// 		 echo '

// <div class="alert alert-warning alert-dismissible" role="alert">
//   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
//   <strong>Warning!</strong>' . $error . ';
// </div>		

// ';			
				echo validation_errors($error);




			}
		} else {
			if(register_user($first_name, $last_name, $username, $email, $password)) {

				set_message("<p class='bg-success text-center'>Please check your email or spam folder for an activation link</p>");

				redirect("index.php");
				
			} else {
				set_message("<p class='bg-success text-center'>Sorry, we could not register the user</p>");
				redirect("index.php");
			}
		}

	} // POST REQUEST


} // FUNCTION

/*****************REGISTER USER***********/

function register_user($first_name, $last_name, $username, $email, $password) {

	$first_name 	= escape($first_name);
	$last_name 		= escape($last_name);
	$username 		= escape($username);
	$email 			= escape($email);
	$password 		= escape($password);


	if(email_exists($email)) {

		return false;

	} else if (username_exists($username)) {

		return false;

	} else {

		$password = md5($password);
		$validation_code = md5($username + microtime());

		$sql = "INSERT INTO users(first_name, last_name, username, email, password, validation_code, active)";

		$sql .= " VALUES('$first_name', '$last_name','$username', '$email', '$password', '$validation_code', '0')";

		$result = query($sql);
		confirm($result);

		$subject = "Activate Account";
		$msg = "

		Please click the link below to activate your account

		http://localhost/activate.php?email=$email&$code=$validation_code
		";

		$header = "From: noreply@yourwebsite.com";


		send_email($email, $subject, $msg, $headers);


		return true;
	}




}



/***********ACTIVATE USER FUNCTIONS**********/

function activate_user() {
	if($_SERVER['REQUEST_METHOD'] == "GET") {

		if(isset($_GET['email'])) {

			$email = clean($_GET['email']);

			$validation_code = clean($_GET['code']);
			$sql = "SELECT id FROM users WHERE email = '".escape($_GET['email'])."' AND validation_code = '".escape($_GET['code'])."' ";

			$result = query($sql);
			confirm($result);

			if(row_count($result) == 1) {

				$sql = "UPDATE users SET active = 1, validation_code = 0 WHERE email = '".escape('email')."'";
			echo "<p class='bg-success'>Your account has been activated. Please LOGIN!</p>";
			}
		} 

	}
}
?>

