<?php
	require $_SERVER['DOCUMENT_ROOT']."fb2/reqd/config.php";
	
	if ($user){ ?>
      </br> <strong> <a href="<?php echo $logoutUrl; ?>">Logout</a></strong>
    <?php } else{ ?>
      <div> </br> <p>To view the contents of the page, <p><a href="<?php echo $loginUrl; ?>"> Login with Facebook</a> </div>
    <?php }
	$host="localhost"; // Host name 
	$username="root"; // Mysql username 
	$password=""; // Mysql password 
	$db_name="test2"; // Database name 
	
	
		$topic = $_POST['topic'];
		$detail = $_POST['detail'];
		$id = $_POST['id'];
		
		$tbl_name="petition_question"; // Table name 
	
		mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
		mysql_select_db("$db_name")or die("cannot select DB");

		$sql=" SELECT * FROM $tbl_name WHERE id=$id";
		$result=mysql_query($sql);
		$rows=mysql_fetch_array($result);
		
		
		$email2 = $rows['email2'];
		$name2 = $rows['name2'];
		$email = $rows['email'];
		
		$name = $rows['name'];
	
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
		echo "</br>mail sent";
		
	}
	
	else
	{
		echo "</br>Not sent". "</br>";
	}
	


	$tbl_name="petition_answer"; // Table name 

// Connect to server and select databse.
	mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
	mysql_select_db("$db_name")or die("cannot select DB");

// Get value of id that sent from hidden field 
$id=$_POST['id'];

// Find highest answer number. 
$sql="SELECT MAX(a_id) AS Maxa_id FROM $tbl_name WHERE question_id='$id'";
$result=mysql_query($sql);
$rows=mysql_fetch_array($result);

// add + 1 to highest answer number and keep it in variable name "$Max_id". if there no answer yet set it = 1 
if ($rows) {
$Max_id = $rows['Maxa_id']+1;
}
else {
$Max_id = 1;
}

// get values that sent from form 
$a_name=$_POST['a_name'];
$a_email=$_POST['a_email'];
$a_answer=$_POST['a_answer']; 


$datetime=date("d/m/y H:i:s"); // create date and time

// Insert answer 
$sql2="INSERT INTO $tbl_name(question_id, a_id, a_name, a_email, a_answer, a_datetime)VALUES('$id', '$Max_id', '$a_name', '$a_email', '$a_answer', '$datetime')";
$result2=mysql_query($sql2);

if($result2){
echo "Successful</br>";
echo "<a href='view_topic.php?id=".$id."&uid=0'>View your vote</a>";

					$facebook->api('/me/feed', 'post', array(
						'message' => ' I have Just Signed a petiton',
						'link' => 'http://apps.facebook.com/endcorr/',
						'name' => $topic,
						'caption' => 'Join Me in this Movement',
						'description' => $detail,
						)
						);

// If added new answer, add value +1 in reply column 
$tbl_name2="petition_question";
$sql3="UPDATE $tbl_name2 SET reply='$Max_id' WHERE id='$id'";
$result3=mysql_query($sql3);
}
else {
echo "ERROR";
}

// Close connection
mysql_close();
?>