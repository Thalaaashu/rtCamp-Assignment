<?php

while(true)
{
	$connection = mysqli_connect("remotemysql.com", "FhYN7E3LgC", "tDjfCwj1bE", "FhYN7E3LgC"); // connect to the db
	if(!$connection) die("Error while connecting to DB"); // if error while connecting then show it

	$html = file_get_contents("https://c.xkcd.com/random/comic"); // select a random comic

	$pos = strpos($html, 'Image URL (for hotlinking/embedding): '); // parse the string for the image
	$html = substr($html, $pos); // delete everything until the link
	$pos = strpos($html, '.png'); // if png photo then select it
	if($pos != false) $html = substr($html, 0, $pos + 4);
	$pos = strpos($html, '.jpg'); // if jpg photo then select it
	if($pos != false) $html = substr($html, 0, $pos + 4);
	$pos = strpos($html, '.gif'); // if gif photo then select it
	if($pos != false) $html = substr($html, 0, $pos + 4);
	$html = str_replace("Image URL (for hotlinking/embedding): ", "", $html); // remove unusable string

	$comic = $html;

	var_dump($comic); // print the comic url

	$query = "SELECT * FROM emails WHERE confirmed = 1"; // select all subscribed emails from the db
	$result = $connection->query($query); // send the query to db

	if($result->num_rows == 0) goto abort; // if there are now rows then end the action

	$results['success'] = 0; // the count of the success emails
	$results['error'] = 0; // the count of the errors

	while($data = $result->fetch_assoc()) // iterate the all emails
	{
		$file = $comic; // rename the file
		$mailto = $data['email']; // mail destination
		$subject = "Your comic"; // subject
		$filename = explode('/', $comic)[4]; // explode for comic name
		$message = '
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
						Your\'ve received your comic :)<br>
						<br>
						<img src="'.$comic.'">
						<br><br>
						<a href="http://localhost/Project5/unsubscribe.php?token='.$data['token'].'" class="submit" button type="button">Click here to unsubscribe</a>
					</div>
					<script type="text/javascript" src="http://localhost/Project5/js/script.js"></script>
				</body>
			</html>'; // the html message
		
		$content = file_get_contents($file); // the content of the file
	  $content = chunk_split(base64_encode($content)); // encode the photo to add them to attach

	  $separator = md5(time()); // random separator
	  $eol = "\r\n"; // new line

	  // main header (multipart mandatory)
	  $headers = "From: Project5 <testemailermessi@gmail.com>" . $eol;
	  $headers .= "MIME-Version: 1.0" . $eol;
	  $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
	  $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
	  $headers .= "This is a MIME encoded message." . $eol;

	  // message
	  $body = "--" . $separator . $eol;
	  $body .= "Content-type:text/html;charset=UTF-8" . $eol;
	  $body .= "Content-Transfer-Encoding: 8bit" . $eol;
	  $body .= $message . $eol;

	  // attachment
	  $body .= "--" . $separator . $eol;
	  $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
	  $body .= "Content-Transfer-Encoding: base64" . $eol;
	  $body .= "Content-Disposition: attachment" . $eol;
	  $body .= $content . $eol;
	  $body .= "--" . $separator . "--";

	  if(mail($mailto, $subject, $body, $headers)) { // send the email
	  	$results['success'] ++; // if success then count it
	  }
	  else {
	  	$results['error'] ++; // count errors
	  }
	}

	abort:
	$connection->close(); // close the connection
	
	sleep(5 * 60); // wait for 5 minutes then do this again
}