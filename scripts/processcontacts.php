<?php
	
	//This script processes comments on all individual sightings pages. It checks for form submition errors prior to inserting comments into the comments table. 


	//require_once('../includes/connection.php');

	#initialize the variables for the form if users want to post a comment
	$name ='';
	$email ='';
	//$website ='';
	$comments ='';
	$regExp ='';
	$errorMsg ='';

    #grab the form data
	$curnum = 0; //will itemize the number of errors we have
    $name = $_POST['name'];
    $email = $_POST['email'];
   // $website = $_POST['website'];
    $comments = $_POST['comments_body'];
	
	#do some injection cleaning
	$name = stripslashes($name);
	$email = stripslashes($email);
	//$website = stripslashes($website);
	$comments = stripslashes($comments);
	
	$name = strip_tags($name);
	$email = strip_tags($email);
//	$website = strip_tags($website);
	$comments = strip_tags($comments);
	
	#verify properly formatted email address
	//$regExp = '/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/';
	//$regExp = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
	$regExp = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})/';
	#check for errors    
     if (strlen($name) < 2) {
	 	 $curnum ++;
         $errorMsg['name'] = '<span class="error">'.$curnum.'. Your name is required</span>';
		}
     if (!preg_match($regExp, $email)) {
	 	 $curnum ++;
	 	 $errorMsg['email'] = '<span class="error">'.$curnum.'. Please enter a valid email address</span>';
	 	}
	 if (strlen ($comments) < 3) {
	 	 $curnum ++; 
         $errorMsg['comments_body'] = '<span class="error">'.$curnum.'. You need to write a message</span>';
		}
		
//	$name = mysql_real_escape_string($name);
//	$email = mysql_real_escape_string($email);
//	$website = mysql_real_escape_string($website);
	//$comments = mysql_real_escape_string($comments);	 
			
	#done with error checking now perform the insert
	if (!$errorMsg) {
		$site_owner_email = 'cicada@masscic.org';
		$site_owner_name = 'Massachusetts Cicadas';
		$comments = nl2br($comments);
		//$comments = str_replace("\n.", "\n..", $comments);
		//str_replace("\r\n.", "\r\n..", $comments)
		$url = $_SERVER['HTTP_REFERER'];
		$htmlBody = '<html>
				<head>
					<style type="text/css" media="screen">
					body {font-family:arial, verdana, sans-serif; background-color: #e3e3e3; font-size: 1em; height: 100%;}
					h3 {font-size: .9em; color:#333333;	margin-bottom: 1em;	margin-left: .45em;}
					p {color: #666;	font-size: .75em; margin-left: .5em; line-height: 1.3em; margin-bottom:1em;}
					ul li {list-style:none;	font-size: .75em;}
					</style>
				</head>
					<body>
					<div style="overflow: hidden; background-color: #e3e3e3; width: 59em;">
						<div style="width: 100%; background-color: #fff; border: 1px solid #ccc; margin: auto;">
							<div align="center"><img src="http://www.masscic.org/images/pageElements/header.jpg" title="Welcome To Massachusetts Cicadas" alt="Welcome To Massachusetts Cicadas" style="height: 190px; width: 950px"></div><br>
								<h3>A Contact Request Has Come Through. Below is the Information.</h3>
								<p>User Details:</p>
									<ul>
										<li><b>Name: </b>'.$name.'</li>
										<li><b>Email: </b>'.$email.'</li>
									</ul>
								<p>User Comments:</p>
								<p><em>'.$comments.'</em></p>
							</div>
						</div>
					</body>
				</html>';
		$textBody = 'Name: '.$name."\r\n" .
					'Email: '.$email."\r\n" .
					//'Website: '.$website."\r\n\n".
					'This is the comment: '."\r\n".$comments."\r\n\n" . 
		
		require_once('../PHPMailer_v5.1/class.phpmailer.php');
		$mail = new PHPMailer ();
		$mail -> IsSMTP ();
		$mail -> From = $email;//who filled out the form email
		$mail -> FromName = $name;//who filled out the form name
		$mail -> Subject = "Inquiry From Massachusetts Cicadas Contact Form.";
		$mail -> AddAddress($site_owner_email, $site_owner_name);//message goes to webmaster
		$mail -> Body = ($htmlBody);
		$mail -> isHTML(true);
		$mail -> AltBody = ($textBody);
		
		$mail -> Host = "mail.masscic.org";
		//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
		$mail -> Port = 50;
		$mail -> SMTPAuth = true;
		$mail -> Username = "cicada@masscic.org";
		$mail -> Password = "Macross123";
		
		$mail -> send();
		
		echo '<li class="success"> Thanks, ' . $name . '. Your message was posted successfully! We will get back to you shortly. </li>';	
	}
	else {

		$response = (isset($errorMsg['name'])) ? "<li>" . $errorMsg['name'] . "</li> \n" : null;
		$response .= (isset($errorMsg['email'])) ? "<li>" . $errorMsg['email'] . "</li> \n" : null;
		$response .= (isset($errorMsg['comments_body'])) ? "<li>" . $errorMsg['comments_body'] . "</li>" : null;
		
		echo $response;
	} # end if there was an error posting


?>
