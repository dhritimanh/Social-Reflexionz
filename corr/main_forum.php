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

$tbl_name="forum_question"; // Table name 


$con = mysql_connect("$host", "$username", "$password");

if (!$con)
	{
		die("cannot connect : " . mysql_error() ); 
	
	}
mysql_select_db("$db_name", $con) or die("cannot select DB");

$sql = "SELECT * FROM $tbl_name ORDER BY id DESC";


$result=mysql_query($sql);
?>
<div align = "center">
	<?php if(isset($user_profile))
		{?>
	<h3> Welcome ,<?php echo " ". $user_profile['name'];?></h3>
    <img src="https://graph.facebook.com/<?php echo $user_profile['id']; ?>/picture" title = "<?php echo $user_profile['name'];?>">
	</br>
	<h4> The Topics Discussed So Far are Below. You can create your own Topic for an Issue</h4>
	</br>
	<?php }?>
	
	
</div>
<table width="90%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<td width="6%" align="center" bgcolor="#E6E6E6"><strong>#</strong></td>
<td width="53%" align="center" bgcolor="#E6E6E6"><strong>Topic</strong></td>
<td width="15%" align="center" bgcolor="#E6E6E6"><strong>Views</strong></td>
<td width="13%" align="center" bgcolor="#E6E6E6"><strong>Replies</strong></td>
<td width="13%" align="center" bgcolor="#E6E6E6"><strong>Date/Time</strong></td>
</tr>

<?php
 

while($rows=mysql_fetch_array($result))
{
	?>
<tr>
<td bgcolor="#FFFFFF"><?php echo $rows['id']; ?></td>
<td bgcolor="#FFFFFF"><a href="view_topic.php?id=<?php echo $rows['id']; ?>&uid=<?php if (isset($user_profile)) {echo $user_profile['id'];} else echo"0";?>"><?php echo $rows['topic']; ?></a><BR></td>
<td align="center" bgcolor="#FFFFFF"><?php echo $rows['view']; ?></td>
<td align="center" bgcolor="#FFFFFF"><?php echo $rows['reply']; ?></td>
<td align="center" bgcolor="#FFFFFF"><?php echo $rows['datetime']; ?></td>
</tr>

<?php
 
}
mysql_close($con);
?>
</table>

<div align = "center">
	</br>
	<a href="create_topic.php"><img src="pic/1.png" title = "create"/></a> 
	
</div>