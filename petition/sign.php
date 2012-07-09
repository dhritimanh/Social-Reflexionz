<?php
	require 'reqd/facebook.php';
	// Create our Application instance 
	$config = array(
  'appId'  => '339280032820737',
  'secret' => '0a85c15f77155863cfc678c794940796',
	);

	$facebook = new Facebook($config);
// Get User ID
	$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me','GET');
  } catch (FacebookApiException $e) {
    $login_url = $facebook->getLoginUrl(); 
    //echo 'Please <a href="' . $login_url . '">login.</a>';
    error_log($e->getType());
    error_log($e->getMessage());
    $user = null;
  }
}
else 
{
		$loginUrl = $facebook->getLoginUrl(array(
       'scope' => 'publish_actions,publish_stream,read_stream,offline_access,photo_upload',
       'redirect_uri' => 'http://apps.facebook.com/sociref/',
     ));

     print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
}

	
	
	
	
	
$host="182.72.63.18"; // Host name 
$username="fc_team_96"; // Mysql username 
$password="Dpa6wUqKJ8NPEsLc"; // Mysql password 
$db_name="fc_team_96"; // Database name 

		$tbl_name2="petition_answer"; // Switch to table "petiton_answer"
		$id=$_GET["id"];
		$con = mysql_connect("$host", "$username", "$password");

		if (!$con)
		{
			die("cannot connect : " . mysql_error() ); 
	
		}
		mysql_select_db("$db_name", $con) or die("cannot select DB");
		
		
		
		$sql2="SELECT question_id,a_name FROM $tbl_name2 WHERE question_id=$id";
		$result2=mysql_query($sql2);
		$rows=mysql_fetch_array($result2);
		$sign = $rows ['question_id'];
		$people = $rows['a_name'];
		if(empty($view))
		{
				echo " NO signs Till now"."</br>";
				echo '<input type="button" value="Go Back !" onclick="history.back(-1)" />';
		}
		else
		{
			echo " There are " . $sign . " signature Till Now ". "</br>" ; 
			
			
			foreach ($people as $value)
			{
				echo $value . "</br>"; 
			}
		echo '<input type="button" value="Go Back !" onclick="history.back(-1)" />';
		}
		mysql_close($con);
?>