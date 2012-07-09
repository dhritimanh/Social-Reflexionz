<?php
if (isset($_POST['submit']))
{ 
	$email2 = $_POST['email2'];
	$name2 = $_POST['name2'];
	$email = $_POST['email'];
	$topic = $_POST['topic'];
	$detail = $_POST['detail'];
	$name = $_POST['name'];
	
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$email2."\r\n";
	$headers .=  'Reply-To: '.$email2.'\n'."\r\n";
	$to = $email;
	$subject = $topic;
	 
	$message= "Greetings\n\n";
	$message .= "Respected Sir,\n\n";
	$message .=	"I just signed the following petition addressed to: ".$name2.".\n";
	
	$message .=	"The Topic of the petition is:\n\n";
	$message .=	"      ------------     \n\n";
	$message .=	$topic."\n\n";
	$message .=	"      ------------     \n\n";
	$message .=	$detail."\n\n";
					
	$message .=	"Thank you very much.\n\n";
	$message .=	"Sincerely,\n";
	$message .=	$name."\n";

	$done = mail($to,$subject,$message,$headers);
	if ($done)
	{
		echo "mail sent";
		
	}
	
	else
	{
		echo "Not sent";
	}
}
?>