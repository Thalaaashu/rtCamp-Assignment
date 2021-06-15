<?php

$error = '';
$success = '';
	
$connection = mysqli_connect("remotemysql.com", "FhYN7E3LgC", "tDjfCwj1bE", "FhYN7E3LgC"); // connect to mysql
if(!$connection) die("Error while connecting to DB"); // if error, show it

if(!isset($_GET['token'])) { $error = "Invalid token."; goto ext; } // if didn't receive token then show error

$token = $_GET['token'];
$query = "SELECT * FROM emails WHERE token = '".$token."'"; // check for emails with this token
$result = $connection->query($query); // send query to db

if($result->num_rows == 0) { $error = "Invalid token."; goto ext; } // if not emails then show error

$query = "UPDATE emails SET confirmed = 1 WHERE token = '".$token."'"; // set column confirmed as to 1 to be able to receive emails
$result = $connection->query($query); // send query to db

$success = "Your email has been confirmed. You will now receive emails with comics."; // send a success message

ext:
mysqli_close($connection); // close the connection with db

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