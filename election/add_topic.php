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

$tbl_name="election_question"; // Table name 

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");



// get data that sent from form 
$topic=$_POST['topic'];
$detail=$_POST['detail'];
$name=$_POST['name'];
$email=$_POST['email'];
$uid = $_POST['uid'];
$sdate = $_POST['sdate'];
$edate = $_POST['edate'];


$arr = $_POST['checkv'];


$datetime=date("d/m/y h:i:s"); //create date time

$sql="INSERT INTO $tbl_name(topic, detail, name, email, sdate, edate, uid)VALUES('$topic', '$detail', '$name', '$email', '$sdate', '$edate','$uid')";
$result=mysql_query($sql);

if($result){ ?>
	<h3>"Successfully Created </h3>
	 <a href=main_forum.php>View your election</a>

	<?php
	$facebook->api('/me/feed', 'post', array(
						'message' => ' An Election has been Created by' . $name,
						'link' => 'http://apps.facebook.com/endcorr/',
						'name' => $topic,
						'caption' => 'Join Me in this Movement',
						'description' => $detail,
						)
						);
						
	$sql2 = "SELECT id FROM $tbl_name where topic = $topic AND detail = $detail";
	$result2=mysql_query($sql2);
	$rows2=mysql_fetch_array($result2);
	
	
	
	$tbl_name2="candidates";
	foreach($arr as $value)
	{
					
					$sql3 = "INSERT INTO $tbl_name2(e_id , value) values ('$rows["id"]' , '$value')";
					$result3=mysql_query($sql3);
					$rows3=mysql_fetch_array($result3);
					if ($result3)
					{
							?> <h3> Entered data </h3>
					<? }
					else
					{
							<?  <h3> Not Entered </h3> <?
					}
			?>
		<h3>Successfully Created </h3>	
<?php	} 
	
	}
else 
{
	echo "ERROR";
}

mysql_close();

?>