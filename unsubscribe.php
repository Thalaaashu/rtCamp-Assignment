<?php

$error = '';
$success = '';

$connection = mysqli_connect("remotemysql.com", "FhYN7E3LgC", "tDjfCwj1bE", "FhYN7E3LgC"); // connect to the db
if(!$connection) die("Error while connecting to DB"); // if error then show it

if(!isset($_GET['token'])) { $error = "Invalid token."; goto ext; } // if script doesn't receive a token then abort it

$token = $_GET['token']; // the token
$query = "SELECT * FROM emails WHERE token = '".$token."'"; // check if there are emails with this token
$result = $connection->query($query); // send query to the db

if($result->num_rows == 0) { $error = "Invalid token."; goto ext; } // if there is no email with this token then show an error

$query = "DELETE FROM emails WHERE token = '".$token."'"; // delete the email from the db
$result = $connection->query($query); // send query to db

$success = "Your have been unsubscribed from the newsletter."; // send success message

ext:
mysqli_close($connection); // close the connection with the database

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
		</div>
		<script type="text/javascript" src="http://localhost/Project5/js/script.js"></script>
	</body>
</html>