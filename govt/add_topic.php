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
if(isset($_POST['submit']))
{
	
	$friends = $facebook->api('/me/friends?fields=id,name,birthday', 'Get');
	foreach($friends['data'] as $friends)  
	{
		$facebook->api('/'.$friends['id'].'/feed', 'post', array(
				'message' => ' A message from '.  $user_profile['name'],
				'link' => 'http://apps.facebook.com/endcorr',
				'name' => $user_profile['name'],
				'caption' => 'Co-operation Needed',
				'description' => $data,
			));
	
	
	}

}
$host="182.72.63.18"; // Host name 
$username="fc_team_96"; // Mysql username 
$password="Dpa6wUqKJ8NPEsLc"; // Mysql password 
$db_name="fc_team_96"; // Database name 
$tbl_name="govt_question"; // Table name 

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

// get data that sent from form 
$topic=$_POST['topic'];
$detail=$_POST['detail'];
$name=$_POST['name'];
$email=$_POST['email'];


$datetime=date("d/m/y h:i:s"); //create date time

$sql="INSERT INTO $tbl_name(topic, detail, name, email, datetime, uid)VALUES('$topic', '$detail', '$name', '$email', '$datetime', 'uid')";
$result=mysql_query($sql);

if($result){
echo "Successful</br>";
echo "<a href=main_forum.php>View your topic</a>"."</br>";

echo " To Notify All your Friends , Press the Button Below";
?>
</br> 
<form id="form1" name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">
<input type = "Button" name = "submit" value = "Notify Friends"/> 
<?php
}
else {
echo "ERROR";
}
mysql_close();

?>