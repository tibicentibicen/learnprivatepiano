<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$name = strip_tags($_POST['name']);
$email = strip_tags($_POST['email']);
$message = strip_tags($_POST['message']);

//server-side validation 

if (empty($name)){
	header('contact-new.html?error=name');
	exit();
}
if (empty($email)){
	header('contact-new?error=email');
	exit();
}
if (empty($message)){
	header('contact-new?error=message');
	exit();
}

require 'vendor/autoload.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 4;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.masscic.org';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'cicada@masscic.org';                 // SMTP username
    $mail->Password = 'Macross123';                           // SMTP password
   // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('cicada@masscic.org', 'Gerry');
    $mail->addAddress('mechaworx@yahoo.com', 'Gerry');     // Add a recipient

    $body = '

		An inquiry has come in from the website<br />

		Name: '. $name .'<br />
		Email: '. $email.'<br /><br />
		Message: '. $message.'<br />
    ';
    
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Website enquiry from ' . $name;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

?>