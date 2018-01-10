<?php
	require "assets/Includes/header.php";
	require "assets/Includes/PHPMailer-master/PHPMailerAutoload.php";
	if ($staff_power == 0) {
		
$mail = new PHPMailer;
		
$mail->setFrom('exile@subatomicgaming.com', 'Exile');
$mail->addAddress('subatomicgaming0@gmail.com', 'SAG0');     // Add a recipient
$mail->addReplyTo('exile@subatomicgaming.com', 'Exile');

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Update your info';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
$mail->send();
	}
?>
