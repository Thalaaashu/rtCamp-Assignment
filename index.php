<?php

$error = '';
$success = '';

$connection = mysqli_connect("remotemysql.com", "FhYN7E3LgC", "tDjfCwj1bE", "FhYN7E3LgC"); // connect to db
if(!$connection) die("Error while connecting to DB"); // if error while connecting then show it

// ON CLICK NEXT
if(!isset($_POST['Send'])) // go next only if user clicks the subscribe button
	goto ext;

if(empty($_POST['email'])) { $error = "Please type your email."; goto ext; } // if user doesn't insert an email then show a error
if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $error = "Invalid email format."; goto ext; } // check for valid email

$query = "SELECT id FROM emails WHERE email = '".$_POST['email']."'"; // check if there are already a row with this email
$result = $connection->query($query); // send query to db

if($result->num_rows != 0) { $error = "Sorry, but this email is already registred."; goto ext; } // if email already registred then show a error

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // random chars available
$charactersLength = strlen($characters);
$token = ''; // the random token
for ($i = 0; $i < 64; $i++) {
  $token .= $characters[rand(0, $charactersLength - 1)]; // choose a random char from the char list
}

$to_email = $_POST['email']; // the email inserted by the user
$subject = "Confirm your email address"; // subject of email
$body = "Hello, sir!\nPlease confirm your email address by pressing on this link: http://localhost/Project5/check.php?token=".$token."\n\nGreetings,\nProject5"; // body of email
$headers = "From: Project5\'s email"; // headers of the email
 
if(mail($to_email, $subject, $body, $headers)) { // send email and if succeed then insert to db & show success message
  $success = "An email was sent to your email. Please confirm your address.";

  $query = "INSERT INTO emails (email, token, confirmed) VALUES ('".$_POST['email']."', '".$token."', 0)";
	$result = $connection->query($query);
} else {
  $error = "Could not sent an email to this address. Please try again."; // if error then show it
}

ext:
mysqli_close($connection); // close the connection to the db


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Project5</title>

		<link rel="stylesheet" type="text/css" href="http://localhost/Project5/css/style.css">

		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<?php if($error != ''): ?>
				<p>
					<div class="error">
						<b>Error!</b><br><br>
						<?php echo $error; ?>
					</div>
				</p>
			<?php endif; ?>
			<?php if($success != ''): ?>
				<p>
					<div class="success">
						<b>Success!</b><br><br>
						<?php echo $success; ?>
					</div>
				</p>
			<?php endif; ?>
			Hello, welcome to <b>Ashish Singh Assignment</b>!<br>
			Here, you can subscribe to a comics emailer. Every 5 minutes you will receive an email.<br>
			<br>
			<form method="POST">
				<input type="email" name="email" class="input" placeholder="your_email@yahoo.com">
				<br><br>
				<input type="submit" name="Send" value="Subscribe" class="submit">
			</form>
			<p>Important! You will have to confirm your email address.</p>
			<p>Hello Everyone !!!! My self Ashish Singh and I welcomed you all to my Assignment and I hope you will liked it :))))</p>
		</div>
		<script type="text/javascript" src="http://localhost/Project5/js/script.js"></script>
	</body>
</html>