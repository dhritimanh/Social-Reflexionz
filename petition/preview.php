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
$tbl_name="petition_question"; // Table name 


$uid = $_GET['uid'];

$con = mysql_connect("$host", "$username", "$password");

if (!$con)
	{
		die("cannot connect : " . mysql_error() ); 
	
	}
	mysql_select_db("$db_name", $con) or die("cannot select DB");

	$sql = "SELECT * FROM $tbl_name where uid = $uid ";

	$result=mysql_query($sql);
	$rows=mysql_fetch_array($result);





?>


<html>
<head>

<head>

<body>
	 <div>Greetings</br> 
		Respected Sir, </br> 
		I just signed the following petition addressed to: <?php echo $rows["name2"];?> </br>  
		The Topic of the petition is: </br>
		--------------------------------- </br>
	 <?php echo '<strong>'.$rows["topic"].'</strong>';?> </br> 
		---------------------------------</br>
	 <?php echo '<em>'.$rows["detail"]. '</em>';?> </br> 
		---------------------------------</br>
	 Thank you very much </br> 
	 Sincerely,</br>
	 <?php echo $rows["name"];?>		
	</div>
	<div>
	<form action = "send.php" method = "post">
		<input type = "hidden" name = "email2" value = "<?php echo $rows['email2']?>">
		<input type = "hidden" name = "name2" value = "<?php echo $rows['name2']?>">
		<input type = "hidden" name = "topic" value = "<?php echo $rows['topic']?>">
		<input type = "hidden" name = "detail" value = "<?php echo $rows['detail']?>">
		<input type = "hidden" name = "name" value = "<?php echo $rows['name']?>">
		<input type = "hidden" name = "email" value = "<?php echo $rows['email']?>">
		<input type = "submit" name = "submit" value = "send email"></input>
	</form>
	</div>
</body>

</html>
